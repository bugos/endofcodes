<?php
    class FileHelperTest extends UnitTest {
        public function setUp() {
            global $config;

            $this->content = 'I shall be written safely';
            // $this->directory = '/tests/helpers/file';
            //$this->filename = $this->directory . 'message.txt';
            $this->filename = 'message.txt';

            //mkdir( $this->directory );
        }
        private function readOnlyDirectory() {
            chmod( $this->directory, 0444 );
            $this->assertThrows(
                function() {
                    safeWrite( $this->filename, $this->content );
                },
                'FileNotWritableException',
                'safeWrite() must throw an Exception when attempting to write to a read-only directory.'
            );
        }
        private function emptyWritableDirectory() {
            //chmod( $this->directory, 0666 );
            $this->assertDoesNotThrow(
                function() {
                    safeWrite( $this->filename, $this->content );
                },
                'FileNotWritableException',
                'safeWrite() must not throw an Exception when attempting to write to an empty, writable directory.'
            );
            $read = file_get_contents( $this->filename );
            $this->assertEquals( $this->content, $read, 'Content read must be the same passed to safeWrite().' );
        }
        private function readOnlyFile() {
            chmod( $this->filename, 0444 );
            $this->assertThrows(
                function() {
                    safeWrite( $this->filename, $this->content );
                },
                'FileNotWritableException',
                'safeWrite() must throw an Exception when attempting to write a read-only file.',
                function( FileNotWritableException $e ) {
                    $this->assertEquals( $this->filename, $e->filename );
                    $this->assertEquals( $this->content, $e->content );
                }
            );
        }
        public function testSafeWrite() {
            //$this->readOnlyDirectory(); // This test is not functional yet.
            $this->emptyWritableDirectory();
            $this->readOnlyFile();
        }
        private function safeUnlink( $filename ) {
            if ( file_exists( $filename ) ) {

                chmod( $filename, 0666 );

                if ( is_dir( $filename ) ) {
                    rmdir( $this->directory );
                }
                else {
                    unlink( $filename );
                }
            }
        }
        public function tearDown() {
            $this->safeUnlink( $this->filename );
            //$this->safeUnlink( $this->directory );
        }
    }

    return new FileHelperTest();
?>
