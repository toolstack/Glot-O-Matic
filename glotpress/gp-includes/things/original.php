<?php
class GP_Original extends GP_Thing {

	var $table_basename = 'originals';
	var $field_names = array( 'id', 'project_id', 'context', 'singular', 'plural', 'references', 'comment', 'status', 'priority', 'date_added' );
	var $non_updatable_attributes = array( 'id', 'path' );

    static $priorities = array( '-2' => 'hidden', '-1' => 'low', '0' => 'normal', '1' => 'high' );
	static $count_cache_group = 'active_originals_count_by_project_id';

	function restrict_fields( $original ) {
		$original->singular_should_not_be('empty');
		$original->status_should_not_be('empty');
		$original->project_id_should_be('positive_int');
		$original->priority_should_be('int');
		$original->priority_should_be('between', -2, 1);
	}

	function normalize_fields( $args ) {
		$args = (array)$args;
		foreach ( array('plural', 'context', 'references', 'comment') as $field ) {
			if ( isset( $args['parent_project_id'] ) ) {
				$args[$field] = $this->force_false_to_null( $args[$field] );
			}
		}

		if ( isset( $args['priority'] ) && !is_numeric( $args['priority'] ) ) {
			$args['priority'] = $this->priority_by_name( $args['priority'] );
			if ( is_null( $args['priority'] ) ) {
				unset( $args['priority'] );
			}
		}

		return $args;
	}

	function by_project_id( $project_id ) {
		return $this->many( "SELECT * FROM $this->table WHERE project_id= %d AND status = '+active'", $project_id );
	}

	function count_by_project_id( $project_id ) {
		if ( false !== ( $cached = wp_cache_get( $project_id, self::$count_cache_group ) ) ) {
			return $cached;
		}
		$count = $this->value( "SELECT COUNT(*) FROM $this->table WHERE project_id= %d AND status = '+active'", $project_id );
		wp_cache_set( $project_id, $count, self::$count_cache_group );
		return $count;
	}


	function by_project_id_and_entry( $project_id, $entry, $status = null ) {
		global $gpdb;

		$entry->plural  = isset( $entry->plural ) ? $entry->plural : null; 
		$entry->context = isset( $entry->context ) ? $entry->context : null; 

		$where = array();
		// now each condition has to contain a %s not to break the sequence
		$where[] = is_null( $entry->context ) ? '(context IS NULL OR %s IS NULL)' : 'BINARY context = %s';
		$where[] = 'BINARY singular = %s';
		$where[] = is_null( $entry->plural ) ? '(plural IS NULL OR %s IS NULL)' : 'BINARY plural = %s';
		$where[] = 'project_id = %d';

		if ( ! is_null( $status ) ) {
			$where[] = $gpdb->prepare( 'status = %s', $status );
		}

		$where = implode( ' AND ', $where );

		return $this->one( "SELECT * FROM $this->table WHERE $where", $entry->context, $entry->singular, $entry->plural, $project_id );
	}

	function import_for_project( $project, $translations ) {
		global $gpdb;
		wp_cache_delete( $project->id, self::$count_cache_group );

		$originals_added = $originals_existing = $originals_obsoleted = 0;

		$all_originals_for_project = $this->many_no_map( "SELECT * FROM $this->table WHERE project_id= %d", $project->id );
		$originals_by_key = array();
		foreach( $all_originals_for_project as $original ) {
			$entry = new Translation_Entry( array( 'singular' => $original->singular, 'plural' => $original->plural, 'context' => $original->context ) );
			$originals_by_key[$entry->key()] = $original;
		}

		foreach( $translations->entries as $entry ) {
			$gpdb->queries = array();
			$data = array('project_id' => $project->id, 'context' => $entry->context, 'singular' => $entry->singular,
				'plural' => $entry->plural, 'comment' => $entry->extracted_comments,
				'references' => implode( ' ', $entry->references ), 'status' => '+active' );
			$data = apply_filters( 'import_original_array', $data );

			// TODO: do not obsolete similar translations
			if ( isset( $originals_by_key[$entry->key()] ) ) {
				$original = $originals_by_key[$entry->key()];

				if ( GP::$original->is_different_from( $data, $original ) ) {
					$this->update( $data, array( 'id' => $original->id ) );
				}

				$originals_existing++;
			}
			else {
				GP::$original->create( $data );
				$originals_added++;
			}
		}

		// Mark previously active, but now removed strings as obsolete
		foreach ( $originals_by_key as $key => $value) {
			if ( ! key_exists( $key, $translations->entries ) && '-obsolete' != $value->status ) {
				$this->update( array('status' => '-obsolete'), array( 'id' => $value->id ) );
				$originals_obsoleted++;
			}
		}

		// Clear cache when the amount of strings are changed
		if ( $originals_added > 0 || $originals_obsoleted > 0 ) {
			gp_clean_translation_sets_cache( $project->id );
		}

		do_action( 'originals_imported', $project->id, $originals_added, $originals_existing, $originals_obsoleted );

		return array( $originals_added, $originals_existing );
	}

	function is_different_from( $data, $original = null ) {
		if ( !$original ) $original = $this;
		foreach( $data as $field => $value ) {
			if ( $original->$field != $value ) return true;
		}
		return false;
	}

	function priority_by_name( $name ) {
		$by_name = array_flip( self::$priorities );
		return isset( $by_name[$name] )? $by_name[$name] : null;
	}
}
GP::$original = new GP_Original();
