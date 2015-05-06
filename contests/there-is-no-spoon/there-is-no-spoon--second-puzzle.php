<?php

class Machine_Check {
	//Coordinates of the node that now in test
	private $current_x = 0;
	private $current_y = 0;

	//Nodes fields details
	private $max_x = 30;
	private $max_y = 30;
	private $min_x = 0;
	private $min_y = 0;
	private $nodes_raw_details = array();
	private $nodes_neighbors_details = array();
	private $nodes_connections_details = array();

	function __construct() {
		$this->setup_system_info();
		$this->gather_connections_info();
		$this->build_connection();
	}

	private function setup_system_info() {
		fscanf( STDIN, "%d",
			$this->max_x // the number of cells on the X axis
		);
		fscanf( STDIN, "%d",
			$this->max_y // the number of cells on the Y axis
		);
		for ( $i = 0; $i < $this->max_y; $i++ ) {
			$this->nodes_raw_details[] = stream_get_line( STDIN, 31, "\n" ); // width characters, each either 0 or .
		}
	}

	private function build_connection() {
		//Iterate over gathered neighbors details lines
		foreach( $this->nodes_neighbors_details as $x => $nodes_line_details ) {
			//Iterate over gathered neighbors details items
			foreach( $nodes_line_details as $y => $node_neighbors_details ) {
				foreach ( $node_neighbors_details as $neighbor_details ) {
					$neighbor_x = $neighbor_details[0];
					$neighbor_y = $neighbor_details[1];
					if (
						$this->is_acceptable_to_connect( $x, $y )
						&& $this->is_acceptable_to_connect( $neighbor_x, $neighbor_y )
					) {
						$this->reduce_connections_amount( $neighbor_x, $neighbor_y );
						$this->reduce_connections_amount( $x, $y );
						echo( "$x $y $neighbor_x $neighbor_y 1\n" );
					}
				}
			}
		}
	}

	private function reduce_connections_amount( $x, $y ) {
		--$this->nodes_connections_details[$x][$y];
	}

	private function is_acceptable_to_connect( $x, $y ) {
		if ( $this->nodes_connections_details[$x][$y] > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	private function gather_connections_info() {
		//Iterate over lines
		for( $y = 0; $y < $this->max_y; $y++ ) {
			//Iterate over line items
			for( $x = 0; $x < $this->max_x; $x++ ) {
				//Check that node not empty
				if ( $this->is_power_node( $x, $y ) ) {
					$neighbors = $this->detect_neighbors( $x, $y );
					$this->nodes_neighbors_details[$x][$y] = $neighbors;
					$this->nodes_connections_details[$x][$y] = (int) $this->nodes_raw_details[ $y ][ $x ];
				}
			}
		}
	}

	private function detect_neighbors( $node_x, $node_y ) {
		$neighbors = array();
		$this->detect_direct_neighbors( $node_x, $node_y, $neighbors );
//		$this->detect_diagonal_neighbors( $node_x, $node_y, $neighbors );
		return $neighbors;
	}

	private function detect_direct_neighbors( $node_x, $node_y, &$neighbors ) {
		//Check at right
		$neighbor_y = $node_y;
		for( $neighbor_x = $node_x + 1; $neighbor_x < $this->max_x; $neighbor_x++ ) {
			if ( $this->is_power_node( $neighbor_x, $neighbor_y ) ) {
				$neighbors[] = array( $neighbor_x, $neighbor_y );
				break;
			}
		}

		//Check at left
		$neighbor_y = $node_y;
		for( $neighbor_x = $node_x - 1; $neighbor_x >= 0; $neighbor_x-- ) {
			if ( $this->is_power_node( $neighbor_x, $neighbor_y ) ) {
				$neighbors[] = array( $neighbor_x, $neighbor_y );
				break;
			}
		}

		//Check at bottom
		$neighbor_x = $node_x;
		for( $neighbor_y = $node_y + 1; $neighbor_y < $this->max_y; $neighbor_y++ ) {
			if ( $this->is_power_node( $neighbor_x, $neighbor_y ) ) {
				$neighbors[] = array( $neighbor_x, $neighbor_y );
				break;
			}
		}

		//Check at top
		$neighbor_x = $node_x;
		for( $neighbor_y = $node_y - 1; $neighbor_y >= 0; $neighbor_y-- ) {
			if ( $this->is_power_node( $neighbor_x, $neighbor_y ) ) {
				$neighbors[] = array( $neighbor_x, $neighbor_y );
				break;
			}
		}
	}


	private function detect_diagonal_neighbors( $node_x, $node_y, &$neighbors ) {
		//Check at right bottom
		for( $neighbor_x = $node_x + 1, $neighbor_y = $node_y + 1; $neighbor_x < $this->max_x && $neighbor_y < $this->max_y; $neighbor_y++, $neighbor_x++  ) {
			if ( $this->is_power_node( $neighbor_x, $neighbor_y ) ) {
				$neighbors[] = array( $neighbor_x, $neighbor_y );
				break;
			}
		}

		//Check at left upper
		for( $neighbor_x = $node_x - 1, $neighbor_y = $node_y - 1; $neighbor_x >= 0 && $neighbor_y >= 0; $neighbor_y--, $neighbor_x--  ) {
			if ( $this->is_power_node( $neighbor_x, $neighbor_y ) ) {
				$neighbors[] = array( $neighbor_x, $neighbor_y );
				break;
			}
		}

		//Check at right upper
		for( $neighbor_x = $node_x + 1, $neighbor_y = $node_y - 1; $neighbor_x < $this->max_x && $neighbor_y >= 0; $neighbor_y--, $neighbor_x++  ) {
			if ( $this->is_power_node( $neighbor_x, $neighbor_y ) ) {
				$neighbors[] = array( $neighbor_x, $neighbor_y );
				break;
			}
		}

		//Check at left bottom
		for( $neighbor_x = $node_x - 1, $neighbor_y = $node_y + 1; $neighbor_x >= 0 && $neighbor_y < $this->max_y; $neighbor_y++, $neighbor_x--  ) {
			if ( $this->is_power_node( $neighbor_x, $neighbor_y ) ) {
				$neighbors[] = array( $neighbor_x, $neighbor_y );
				break;
			}
		}
	}


	private function is_power_node( $x, $y ) {
		$node_connections_num = $this->nodes_raw_details[$y][$x];
		if ( 0 != (int) $node_connections_num ) {
			return true;
		}
	}
}

new Machine_Check();