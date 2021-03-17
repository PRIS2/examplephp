<?php

class AwesomeAddon {

    private $settings;

    public function __construct( $settings ) {
        $this->set_settings( $settings );
    }

    protected function set_settings( $settings ) {
        if ( ! is_array( $settings ) ) {
            throw new \Exception( 'Invalid settings' );
        }

        $this->settings = $settings;
    }

    protected function do_something_awesome() {
        //...
    }

}

class EvenMoreAwesomeAddon {

    private $settings;

    public function __construct( $settings ) {
        $this->set_settings( $settings );
    }

    protected function set_settings( $settings ) {
        if ( ! is_array( $settings ) ) {
            throw new \Exception( 'Invalid settings' );
        }

        $this->settings = $settings;
    }

    protected function do_something_even_more_awesome() {
        //...
    }

}
