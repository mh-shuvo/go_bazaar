<?php
return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => 'Digital Union',
	'subject'               => 'Members Info',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	'font_path'             => base_path('/resources/fonts/'),
	'font_data'             => [
									'bangla' => [
										'R'  => 'SolaimanLipi.ttf',    // regular font
										'B'  => 'SolaimanLipi.ttf',       // optional: bold font
										'I'  => 'SolaimanLipi.ttf',     // optional: italic font
										'BI' => 'SolaimanLipi.ttf', // optional: bold-italic font
										'useOTL' => 0xFF,    
										'useKashida' => 75, 
									],
									'roboto' => [
										'R'  => 'Roboto-Regular.ttf',    // regular font
										'B'  => 'Roboto-Bold.ttf',       // optional: bold font
										'I'  => 'Roboto-Italic.ttf',     // optional: italic font
										'BI' => 'Roboto-BoldItalic.ttf', // optional: bold-italic font
										'useOTL' => 0xFF,    
										'useKashida' => 75, 
									],
								]
];