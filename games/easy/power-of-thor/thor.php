<?php
class Thor {
	//Thor's position
	private $current_x = 0;
	private $current_y = 0;

	//Map details
	private $max_x = 40;
	private $max_y = 18;
	private $min_x = 0;
	private $min_y = 0;
	private $destination_x = null;
	private $destination_y = null;

	function __construct() {
		//Setup initial info
		fscanf(STDIN, "%d %d %d %d",
			$this->destination_x, // the X position of the light of power
			$this->destination_y, // the Y position of the light of power
			$this->current_x, // Thor's starting X position
			$this->current_y // Thor's starting Y position
		);
	}

	public function walk() {
		while ( true )  {
			if ( $this->maybe_diagonal_step() ) {
				continue;
			}

			if ( $this->maybe_step_horizontally() ) {
				continue;
			}

			if ( $this->maybe_step_vertically() ) {
				continue;
			}
		}
	}

	/**
	 * Return true if done successful step
	 * @return bool
	 */
	private function maybe_step_horizontally() {
		if ( $this->destination_x > $this->current_x ) {
			$direction = 'E';
			echo("$direction\n");
			$this->calculate_new_postition( $direction );
			return true;
		} elseif ( $this->destination_x < $this->current_x ) {
			$direction = 'W';
			echo("$direction\n");
			$this->calculate_new_postition( $direction );
			return true;
		}

		return false;
	}

	/**
	 * Return true if done successful step
	 * @return bool
	 */
	private function maybe_diagonal_step() {
		if ( $this->destination_x < $this->current_x && $this->destination_y < $this->current_y ) {
			$direction = 'NW';
			echo "$direction\n";
			$this->calculate_new_postition( $direction );
			return true;
		} elseif ( $this->destination_x > $this->current_x && $this->destination_y < $this->current_y ) {
			$direction = 'NE';
			echo "$direction\n";
			$this->calculate_new_postition( $direction );
			return true;
		} elseif ( $this->destination_x < $this->current_x && $this->destination_y > $this->current_y ) {
			$direction = 'SW';
			$this->calculate_new_postition( $direction );
			echo "$direction\n";
			return true;
		} elseif ( $this->destination_x > $this->current_x && $this->destination_y > $this->current_y ) {
			$direction = 'SE';
			echo "$direction\n";
			$this->calculate_new_postition( $direction );
			return true;
		}

		return false;
	}

	/**
	 * Return true if done successful step
	 * @return bool
	 */
	private function maybe_step_vertically() {
		if ( $this->destination_y > $this->current_y ) {
			echo("S\n");
			$this->calculate_new_postition( '-y' );
			return true;
		} elseif ( $this->destination_y < $this->current_y ) {
			$this->calculate_new_postition( '-y' );
			echo("N\n");
			return true;
		}

		return false;
	}

	private function calculate_new_postition( $direction ) {
		switch ( $direction ) {
			case 'N':
				--$this->current_y;
				break;
			case 'S':
				++$this->current_y;
				break;
			case 'E':
				++$this->current_x;
				break;
			case 'W':
				--$this->current_x;
				break;
			case 'NW':
				--$this->current_x;
				--$this->current_y;
				break;
			case 'NE':
				++$this->current_x;
				--$this->current_y;
				break;
			case 'SE':
				++$this->current_x;
				++$this->current_y;
				break;
			case 'SW':
				--$this->current_x;
				++$this->current_y;
				break;
		}
	}
}
$thor = new Thor();
$thor->walk();

