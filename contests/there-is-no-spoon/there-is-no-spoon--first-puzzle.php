<?php

class Machine_Check {
	//Settings
	private $debug = true;

	//Coordinates of the node that now in test
	private $current_x = 0;
	private $current_y = 0;

	//Nodes fields details
	private $max_x = 30;
	private $max_y = 30;
	private $min_x = 0;
	private $min_y = 0;
	private $nodes_details = array();

	function __construct() {
		$this->setup_system_info();
		$this->check();
	}

	private function setup_system_info() {
		fscanf( STDIN, "%d",
			$this->max_x // the number of cells on the X axis
		);
		fscanf( STDIN, "%d",
			$this->max_y // the number of cells on the Y axis
		);
		for ( $i = 0; $i < $this->max_y; $i++ ) {
			$this->nodes_details[] = stream_get_line( STDIN, 31, "\n" ); // width characters, each either 0 or .
		}

		if ( $this->debug ) {
			error_log( var_export( 'nodes details:', true ) );
			error_log( var_export( $this->nodes_details, true ) );
		}
	}

	private function check() {
		//Iterate over lines
		for( $y = 0; $y < $this->max_y; $y++ ) {
			//Iterate over line items
			for( $x = 0; $x < $this->max_x; $x++ ) {
				//Check that node not empty
				if ( $this->is_power_node( $x, $y ) ) {
					if ( $this->debug ) {
						error_log( var_export( 'power node coordinates:', true ) );
						error_log( var_export( array( $x, $y ), true ) );
					}
					$check_result = $x . ' ' . $y;
					$check_result .= $this->detect_neighbor( $x, $y, 'right' );
					$check_result .= $this->detect_neighbor( $x, $y, 'bottom' );
					echo $check_result . "\n";
				}
			}
		}
	}

	private function detect_neighbor( $node_x, $node_y, $at ) {
		//Calc neighbor coordinates
		if ( 'right' == $at ) {
			$neighbor_y = $node_y;
			for( $neighbor_x = $node_x + 1; $neighbor_x < $this->max_x; $neighbor_x++ ) {
				if ( $this->is_power_node( $neighbor_x, $neighbor_y ) ) {
					if ( $this->debug ) {
						error_log( var_export( 'right neighbor coordinates:', true ) );
						error_log( var_export( array( $neighbor_x, $neighbor_y ), true ) );
					}
					return ' ' . $neighbor_x . ' ' . $neighbor_y;
				}
			}
		} elseif ( 'bottom' == $at ) {
			$neighbor_x = $node_x;
			for( $neighbor_y = $node_y + 1; $neighbor_y < $this->max_y; $neighbor_y++ ) {
				if ( $this->is_power_node( $neighbor_x, $neighbor_y ) ) {
					if ( $this->debug ) {
						error_log( var_export( 'bottom neighbor coordinates:', true ) );
						error_log( var_export( array( $neighbor_x, $neighbor_y ), true ) );
					}
					return ' ' . $neighbor_x . ' ' . $neighbor_y;
				}
			}
		}

		//No proper neighbor found
		return ' -1 -1';
	}

	private function is_power_node( $x, $y ) {
		$node_state = $this->nodes_details[$y][$x];
		if ( '0' == $node_state ) {
			return true;
		}
	}
}

new Machine_Check();