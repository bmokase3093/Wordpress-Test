<?php

/*-----------------------------------------------------------------------------------*/
/*	Accordion Config
/*-----------------------------------------------------------------------------------*/
$r = rand(0,999);
$vibe_shortcodes['accordion'] = array(
    'params' => array(),
    'no_preview' => true,
    'params' => array(
        'open_first' => array(
			'type' => 'select',
			'label' => __('Open first', 'wplms'),
			'desc' => __('First accordion will be open by default', 'wplms'),
			'options' => array(
				0 => __('No','wplms'),
				1 => __('Yes','wplms'),
			)
		),
    ),
    'shortcode' => '[agroup first="{{open_first}}" connect="'.$r.'"] {{child_shortcode}}  [/agroup]',
    'popup_title' => __('Insert Accordion Shortcode', 'wplms'),
    'child_shortcode' => array(
        'params' => array(
            'title' => array(
			'type' => 'text',
			'label' => __('Accordion Title 1', 'wplms'),
			'desc' => __('Add the title of the accordion', 'wplms'),
			'std' => 'Title'
		),
		'content' => array(
			'std' => 'Content',
			'type' => 'textarea',
			'label' => __('Accordion Content', 'wplms'),
			'desc' => __('Add the content. Accepts HTML & other Shortcodes.', 'wplms'),
		),
              ),
        'shortcode' => '[accordion title="{{title}}" connect="'.$r.'"] {{content}} [/accordion]',
        'clone_button' => __('Add Accordion Toggle', 'wplms')
    )
);

/*-----------------------------------------------------------------------------------*/
/*	Button Config
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['button'] = array(
	'no_preview' => false,
	'params' => array(
		'url' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Link URL', 'wplms'),
			'desc' => __('Add the button\'s url eg http://www.example.com', 'wplms')
		),
        'class' => array(
			'std' => '',
			'type' => 'select_hide',
			'label' => __('Button Style', 'wplms'),
			'desc' => __('Select button style', 'wplms'),
                        'options' => array(
				'' => 'Base',
				'primary' => 'Primary',
				'blue' => 'Blue',
				'green' => 'Green',
                'other' => 'Custom',
			),
            'level' => 7
		),
		'bg' => array(
			'type' => 'color',
			'label' => __('Background color', 'wplms'),
			'desc' => __('Select the button\'s size', 'wplms')
		),
                'hover_bg' => array(
			'type' => 'color',
			'label' => __('Hover Bg color', 'wplms'),
			'desc' => __('Select the button\'s on hover background color ', 'wplms')
		),
                'color' => array(
			'type' => 'color',
			'label' => __('Text color', 'wplms'),
			'desc' => __('Select the button\'s text color', 'wplms')
		),
                'size' => array(
			'type' => 'slide',
			'label' => __('Font Size', 'wplms'),
                        'min' => 0,
                        'max' => 100,
                        'std' => 0,
		),
		'width' => array(
			'type' => 'slide',
			'label' => __('Width', 'wplms'),
                        'min' => 0,
                        'max' => 500,
                        'std' => 0,
		),
                'height' => array(
			'type' => 'slide',
			'label' => __('Height', 'wplms'),
                        'min' => 0,
                        'max' => 100,
                        'std' => 0,
		),
		'radius' => array(
			'type' => 'slide',
			'label' => __('Border Radius', 'wplms'),
                        'min' => 0,
                        'max' => 150,
                        'std' => 0
		),
		'target' => array(
			'type' => 'select',
			'label' => __('Button Target', 'wplms'),
			'desc' => __('_self = open in same window. _blank = open in new window', 'wplms'),
			'options' => array(
				'_self' => '_self',
				'_blank' => '_blank'
			)
		),
            'content' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Button Anchor', 'wplms'),
			'desc' => __('Replace button label with the text you enter.', 'wplms'),
		)
	),
	'shortcode' => '[button url="{{url}}" class="{{class}}" bg="{{bg}}" hover_bg="{{hover_bg}}" size="{{size}}" color="{{color}}" radius="{{radius}}" width="{{width}}"  height="{{height}}"  target="{{target}}"] {{content}} [/button]',
	'popup_title' => __('Insert Button Shortcode', 'wplms')
);


/*-----------------------------------------------------------------------------------*/
/*	Columns Config
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['columns'] = array(
	'params' => array(),
	'shortcode' => ' {{child_shortcode}} ', // as there is no wrapper shortcode
	'popup_title' => __('Insert Columns Shortcode', 'wplms'),
	'no_preview' => true,
	
	// child shortcode is clonable & sortable
	'child_shortcode' => array(
		'params' => array(
			'column' => array(
				'type' => 'select',
				'label' => __('Column Type', 'wplms'),
				'desc' => __('Select the type, ie width of the column.', 'wplms'),
				'options' => array(
                    'one_fifth' => 'One Fifth',
                    'one_fourth' => 'One Fourth',
					'one_third' => 'One Third',
                    'two_fifth' => 'Two Fifth',
					'one_half' => 'One Half',
                    'three_fifth' => 'Three Fifth',
                    'two_third' => 'Two Thirds',
					'three_fourth' => 'Three Fourth',
                    'four_fifth' => 'Four Fifth',
				)
			),
                        'first' => array(
				'type' => 'select',
				'label' => __('Column Type', 'wplms'),
				'desc' => __('Select the type, ie width of the column.', 'wplms'),
				'options' => array(
                                        '' => 'Default',
                                        'first' => 'First in Row (from Left)',
				)
			),
			'content' => array(
				'std' => '',
				'type' => 'textarea',
				'label' => __('Column Content', 'wplms'),
				'desc' => __('Add the column content.', 'wplms'),
			)
		),
		'shortcode' => '[{{column}} first={{first}}] {{content}} [/{{column}}] ',
		'clone_button' => __('Add Column', 'wplms')
	)
);

/*-----------------------------------------------------------------------------------*/
/*	Counter Config
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['counter'] = array(
	'no_preview' => true,
	'params' => array(
        'min' => array(
			'std' => 0,
			'type' => 'number',
			'label' => __('Start value of counter', 'wplms'),
			'desc' => __('Add a starting number', 'wplms'),
		),
		'max' => array(
			'std' => 100,
			'type' => 'number',
			'label' => __('Maximum value of counter', 'wplms'),
			'desc' => __('Add the Tooltip text', 'wplms'),
		),
		'delay' => array(
			'std' => 3,
			'type' => 'number',
			'label' => __('Total delay in finishing counter', 'wplms'),
			'desc' => __('Add the total duration of counter increment', 'wplms'),
		),
		'increment' => array(
			'std' => 1,
			'type' => 'number',
			'label' => __('Increment unit', 'wplms'),
			'desc' => __('Increment the counter by this value', 'wplms'),
		),
	),
	'shortcode' => '[number_counter min="{{min}}" max="{{max}}" delay="{{delay}}" increment="{{increment}}"]',
	'popup_title' => __('Insert Counter Shortcode', 'wplms')
);

/*-----------------------------------------------------------------------------------*/
/*	Countdown Config
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['countdown'] = array(
	'no_preview' => true,
	'params' => array(
		'date' => array(
			'std' => '',
			'type' => 'date',
			'label' => __('Countdown Date','wplms'),
			'desc' => __('Date until the countdown timer will run', 'wplms'),
		),
        'days' => array(
			'std' => 0,
			'type' => 'number',
			'label' => __('Countdown Days', 'wplms'),
			'desc' => __('Number of days in the countdown timer', 'wplms'),
		),
		'hours' => array(
			'std' => 0,
			'type' => 'number',
			'label' => __('Countdown hours', 'wplms'),
			'desc' => __('Number of hours in the countdown timer', 'wplms'),
		),
		'minutes' => array(
			'std' => 0,
			'type' => 'number',
			'label' => __('Countdown minutes', 'wplms'),
			'desc' => __('Number of minutes in the countdown timer', 'wplms'),
		),
		'seconds' => array(
			'std' => 0,
			'type' => 'number',
			'label' => __('Countdown seconds', 'wplms'),
			'desc' => __('Number of seconds in the countdown timer', 'wplms'),
		),
		'size' => array(
			'std' => 1,
			'type' => 'number',
			'label' => __('Timer Size', 'wplms'),
			'desc' => __('Size of the timer', 'wplms'),
		),
	),
	'shortcode' => '[countdown_timer date="{{date}}" days="{{days}}" hours="{{hours}}" minutes="{{minutes}}" seconds="{{seconds}}" size="{{size}}"]',
	'popup_title' => __('Insert Countdown Shortcode', 'wplms')
);
/*-----------------------------------------------------------------------------------*/
/*	Icon Config
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['icons'] = array(
	'no_preview' => true,
	'params' => array(
		'icon' => array(
					'type' => 'icon',
					'label' => __('Icon type', 'wplms'),
					'desc' => __('Select Icon type', 'wplms'),
					
                 ),
                 'size' => array(
					'type' => 'slide',
					'label' => __('Icon Size', 'wplms'),
					'desc' => __('Icon Size', 'wplms'),
					'min' => 0,
                    'max' => 100,
                    'std' => 0,
                 ),
                 
                 'class' => array(
					'std' => '',
					'type' => 'select_hide',
					'label' => __('Custom Style', 'wplms'),
					'desc' => __('icon style', 'wplms'),
                    'options' => array(
								'' => 'Text Style',
                                'other' => 'Custom',
					),
		            'level' => 6
				),
                 'color' => array(
					'type' => 'color',
					'label' => __('Icon Color', 'wplms'),
					'desc' => __('Icon Color', 'wplms')
                 )
                 ,
                 'bg' => array(
					'type' => 'color',
					'label' => __('Icon Bg Color', 'wplms'),
					'desc' => __('Icon Background color', 'wplms'),
                 ),
                 'hovercolor' => array(
					'type' => 'color',
					'label' => __('Icon Hover Color', 'wplms'),
					'desc' => __('Icon Color', 'wplms'),
                 )
                 ,
                 'hoverbg' => array(
					'type' => 'color',
					'label' => __('Icon Hover Bg Color', 'wplms'),
					'desc' => __('Icon Background color', 'wplms'),
                 ),
                 'padding' => array(
					'type' => 'slide',
					'label' => __('Icon padding', 'wplms'),
					'desc' => __('Icon Background padding', 'wplms'),
					'min' => 0,
                                        'max' => 100,
                                        'std' => 0,
                 ),
                 'radius' => array(
					'type' => 'slide',
					'label' => __('Icon Bg Radius', 'wplms'),
					'desc' => __('Icon Background radius', 'wplms'),
					'min' => 0,
                                        'max' => 100,
                                        'std' => 0,
                 ),
                 
		
	),
	'shortcode' => '[icon icon="{{icon}}" size="{{size}}" color="{{color}}" bg="{{bg}}" hovercolor="{{hovercolor}}" hoverbg="{{hoverbg}}" padding="{{padding}}" radius="{{radius}}"]',
	'popup_title' => __('Insert Icon Shortcode', 'wplms')
);


/*-----------------------------------------------------------------------------------*/
/*	Alert Config
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['alert'] = array(
	'no_preview' => true,
	'params' => array(
		'style' => array(
			'type' => 'select_hide',
			'label' => __('Alert Style', 'wplms'),
			'desc' => __('Select the alert\'s style, ie the alert colour', 'wplms'),
			'options' => array(
				'block' => 'Orange',
				'info' => 'Blue',
				'error' => 'Red',
				'success' => 'Green',
                                'other' => 'Custom'
			),
                        'level' => 3
		),
            'bg' => array(
					'type' => 'color',
					'label' => __('Alert Bg Color', 'wplms'),
					'desc' => __('Background color', 'wplms'),
                 ),
            'border' => array(
					'type' => 'color',
					'label' => __('Alert Border Color', 'wplms'),
					'desc' => __('Border color', 'wplms'),
                 ),
            'color' => array(
					'type' => 'color',
					'label' => __('Text Color', 'wplms'),
					'desc' => __('Alert Text color', 'wplms'),
                 ),
		'content' => array(
			'std' => 'Your Alert/Information Message!',
			'type' => 'textarea',
			'label' => __('Alert Text', 'wplms'),
			'desc' => __('Add the alert\'s text', 'wplms'),
		)
		
	),
	'shortcode' => '[alert style="{{style}}" bg="{{bg}}" border="{{border}}" color="{{color}}"] {{content}} [/alert]',
	'popup_title' => __('Insert Alert Shortcode', 'wplms')
);

/*-----------------------------------------------------------------------------------*/
/*	Tooltip Config
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['tooltip'] = array(
	'no_preview' => true,
	'params' => array(
        'tip' => array(
			'std' => 'Tip content!',
			'type' => 'textarea',
			'label' => __('Tooltip Text', 'wplms'),
			'desc' => __('Add the Tooltip text', 'wplms'),
		),
		'content' => array(
			'std' => 'Tooltip',
			'type' => 'text',
			'label' => __('Tooltip Anchor', 'wplms'),
			'desc' => __('Add the Tooltip anchor', 'wplms'),
		),
		
	),
	'shortcode' => '[tooltip tip="{{tip}}"] {{content}} [/tooltip]',
	'popup_title' => __('Insert Tooltip Shortcode', 'wplms')
);



/*-----------------------------------------------------------------------------------*/
/*	RoundProgressBar
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['roundprogress'] = array(
	'no_preview' => true,
	'params' => array(
		'percentage' => array(
			'type' => 'text',
			'label' => __('Percentage Cover', 'wplms'),
			'desc' => __('Only number eg:20', 'wplms'),
			'std' => '20'
		),
                'style' => array(
			'type' => 'select',
			'label' => __('Style', 'wplms'),
			'desc' => __('Tron or Custom', 'wplms'),
			'options' => array(
				'' => 'Tron',
				'other' => 'Custom'
			)
		),
                'radius' => array(
			'std' => '200',
			'type' => 'text',
			'label' => __('Circle Diameter', 'wplms'),
			'desc' => __('In pixels eg: 100', 'wplms'),
		),
                'thickness' => array(
			'std' => '20',
			'type' => 'text',
			'label' => __('Circle Thickness', 'wplms'),
			'desc' => __('In percentage', 'wplms'),
		),
                 'color' => array(
					'type' => 'color',
					'label' => __('Progress  Text Color', 'wplms'),
					'desc' => __('Progress  Text color', 'wplms'),
                 ),
                 'bg_color' => array(
					'type' => 'color',
					'label' => __('Progress Circle Color', 'wplms'),
					'desc' => __('Progress Circle color', 'wplms'),
                 ),
		'content' => array(
			'std' => '20%',
			'type' => 'text',
			'label' => __('Some Content', 'wplms'),
			'desc' => __('like : 20% Skill, shortcodes/html allowed', 'wplms'),
		),
		
	),
	'shortcode' => '[roundprogress style="{{style}}" color="{{color}}" bg_color="{{bg_color}}" percentage="{{percentage}}" radius="{{radius}}" thickness="{{thickness}}"] {{content}} [/roundprogress]',
	'popup_title' => __('Insert Round Progress Shortcode', 'wplms')
);



/*-----------------------------------------------------------------------------------*/
/*	ProgressBar
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['progressbar'] = array(
	'no_preview' => true,
	'params' => array(
		'percentage' => array(
			'type' => 'text',
			'label' => __('Percentage Cover', 'wplms'),
			'desc' => __('Only number eg:20', 'wplms'),
			'std' => '20'
		),
		'content' => array(
			'std' => '20%',
			'type' => 'text',
			'label' => __('Some Content', 'wplms'),
			'desc' => __('like : 20% Skill, shortcodes/html allowed', 'wplms'),
		),
		'color' => array(
			'type' => 'select_hide',
			'label' => __('Color', 'wplms'),
			'desc' => __('Select progressbar color', 'wplms'),
			'options' => array(
				'' => 'Default',
                'other' => 'Custom',
			),
                        'level' => 2
		),

        'bar_color' => array(
			'type' => 'color',
			'label' => __('Bar Color', 'wplms'),
			'desc' => __('Bar color', 'wplms'),
         ),
        'bg' => array(
			'type' => 'color',
			'label' => __('Bar Background Color', 'wplms'),
			'desc' => __('Bar Background color', 'wplms'),
         ),
	),
	'shortcode' => '[progressbar color="{{color}}" percentage="{{percentage}}" bg={{bg}} bar_color={{bar_color}}] {{content}} [/progressbar]',
	'popup_title' => __('Insert Progressbar Shortcode', 'wplms')
);


/*-----------------------------------------------------------------------------------*/
/*	Tabs Config
/*-----------------------------------------------------------------------------------*/
$r = rand(0,999);
$vibe_shortcodes['tabs'] = array(
    'params' => array(),
    'no_preview' => true,
    'params' => array(
            'style' => array(
                'std' => '',
                'type' => 'select',
                'label' => __('Tabs Style', 'wplms'),
                'desc' => __('select a style', 'wplms'),
                'options' => array(
                    '' => 'Top Horizontal',
                    'tabs-left' => 'Left Vertical',
                    'tabs-right' => 'Right Vertical'
                )
            ),
            'theme' => array(
                'std' => '',
                'type' => 'select',
                'label' => __('Tabs theme', 'wplms'),
                'desc' => __('select a theme', 'wplms'),
                'options' => array(
                    '' => 'Light',
                    'dark' => 'Dark'
                )
            ),
        ),
    'shortcode' => '[tabs style="{{style}}" theme={{theme}} connect="'.$r.'"] {{child_shortcode}}  [/tabs]',
    'popup_title' => __('Insert Tab Shortcode', 'wplms'),
    
    'child_shortcode' => array(
        'params' => array(
            'title' => array(
                'std' => 'Title',
                'type' => 'text',
                'label' => __('Tab Title', 'wplms'),
                'desc' => __('Title of the tab', 'wplms'),
            ),  
            'icon' => array(
            			'type' => 'icon',
            			'label' => __('Title Icon', 'wplms'),
            			'desc' => __('Select Icon type', 'wplms'),
            			),   
            'content' => array(
                'std' => 'Tab Content',
                'type' => 'textarea',
                'label' => __('Tab Content', 'wplms'),
                'desc' => __('Add the tabs content', 'wplms')
            )
        ),
        'shortcode' => '[tab title="{{title}}" icon="{{icon}}" connect="'.$r.'"] {{content}} [/tab]',
        'clone_button' => __('Add Tab', 'wplms')
    )
);


/*-----------------------------------------------------------------------------------*/
/*	Note Config
/*-----------------------------------------------------------------------------------*/


$vibe_shortcodes['note'] = array(
	'no_preview' => true,
	'params' => array(
            
		'style' => array(
				'std' => 'default',
				'type' => 'select_hide',
				'label' => __('Background Color', 'wplms'),
				'desc' => __('Background color & theme of note', 'wplms'),
                                'options' => array(
					'' => 'Default',
                                        'other' => 'Custom'
				),
                                'level' => 3
			),
                'bg' => array(
                        'label' => 'Background Color',
                        'desc'  => 'Background color',
                        'type'  => 'color'
                ),
                'border' => array(
                        'label' => 'Border Color',
                        'desc'  => 'border color',
                        'type'  => 'color'
                ),
                'color' => array(
                        'label' => 'Text Color',
                        'desc'  => 'text color',
                        'type'  => 'color'
                ),
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __('Content', 'wplms'),
			'desc' => __('Note Content, supports HTML/Shortcodes', 'wplms'),
		)
		
	),
	'shortcode' => '[note style="{{style}}" bg="{{bg}}" border="{{border}}" bordercolor="{{bordercolor}}" color="{{color}}"] {{content}} [/note]',
	'popup_title' => __('Insert Note Shortcode', 'wplms')
);


/*-----------------------------------------------------------------------------------*/
/*	DIVIDER Config
/*-----------------------------------------------------------------------------------*/


$vibe_shortcodes['divider'] = array(
	'no_preview' => true,
	'params' => array(
		'style' => array(
				'std' => 'clear',
				'type' => 'text',
				'label' => __('Divider Class', 'wplms'),
				'desc' => __('clear : To begin form new line. Change Size using : one_third,one_fourth,one_fifth,two_third. Use multiple styles space separated', 'wplms'),
			)
		
	),
	'shortcode' => '[divider style="{{style}}"]',
	'popup_title' => __('Insert Divider Shortcode', 'wplms')
);

/*-----------------------------------------------------------------------------------*/
/*	Tagline Config
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['tagline'] = array(
	'no_preview' => true,
	'params' => array(
		'style' => array(
			'type' => 'select_hide',
			'label' => __('Tagline Style', 'wplms'),
			'desc' => __('Select the Tagline style', 'wplms'),
			'options' => array(
				'boxed' => 'Boxed',
				'tagfullwidth' => 'Fullwidth',
                                'other' => 'Custom Boxed'
			),
                    'level' => 4
                    ),
                'bg' => array(
                        'label' => 'Background Color',
                        'desc'  => 'Background color',
                        'type'  => 'color'
                ),
                'border' => array(
                        'label' => 'Overall Border Color',
                        'desc'  => 'border color',
                        'type'  => 'color'
                ),
                'bordercolor' => array(
                        'label' => 'Left Border Color',
                        'desc'  => 'Default color : Theme Primary color',
                        'type'  => 'color'
                ),
                'color' => array(
                        'label' => 'Text Color',
                        'desc'  => 'Default color : Theme text color',
                        'type'  => 'color'
                ),
		'content' => array(
			'std' => 'Tagline Supports HTML',
			'type' => 'textarea',
			'label' => __('Tagline', 'wplms'),
			'desc' => __('Supports HTML content', 'wplms'),
		)
		
	),
	'shortcode' => '[tagline style="{{style}}" bg="{{bg}}" border="{{border}}" bordercolor="{{bordercolor}}" color="{{color}}"] {{content}} [/tagline]',
	'popup_title' => __('Insert Tagline Shortcode', 'wplms')
);



/*-----------------------------------------------------------------------------------*/
/*	Popupss Config
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['popups'] = array(
	'no_preview' => true,
	'params' => array(
                'id' => array(
                'std' =>'',
				'type' => 'text',
				'label' => __('Enter Popup ID', 'wplms'),
			),  
                'classes' => array(
                                'type' => 'select',
                                'label' => __('Anchor Style', 'wplms'),
                                'options' => array(
				    'default' => 'Default',
		                    'btn' =>  'Button',
		                    'btn primary' =>  'Primary Button',
                                        )
                                    ),    
                    'content' => array(
                        'std' =>'',
			'type' => 'textarea',
			'label' => __('Popup/Modal Anchor', 'wplms'),
			'desc' => __('Supports HTML & Shortcodes', 'wplms')
			),
		    'auto' => array(
                        'std' =>'',
			'type' => 'select',
			'label' => __('Show Popup on Page-load', 'wplms'),
                        'options' => array(1 => 'Yes',0 => 'No')
			), 
		
	),
	'shortcode' => '[popup id="{{id}}" auto="{{auto}}" classes="{{classes}}"] {{content}} [/popup] ',
	'popup_title' => __('Insert Popups Shortcode', 'wplms')
);

/*-----------------------------------------------------------------------------------*/
/*	Testimonials Config
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['testimonial'] = array(
	'no_preview' => true,
	'params' => array(
                'id' => array(
                'std' =>'',
				'type' => 'text',
				'label' => __('Enter Testimonial ID', 'wplms'),
			),
             	'length' => array(
                'std' =>'100',
				'type' => 'text',
				'label' => __('Number of Characters to show', 'wplms'),
                'desc' => __('If number of characters entered above is less than Testimonial Post length, Read more link will appear', 'wplms'), 
			),
	),
	'shortcode' => '[testimonial id="{{id}}" length={{length}}]',
	'popup_title' => __('Insert Testimonial Shortcode', 'wplms')
);

/*-----------------------------------------------------------------------------------*/
/*	COURSE Config
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['course'] = array(
	'no_preview' => true,
	'params' => array(
                'id' => array(
                'std' =>'',
				'type' => 'text',
				'label' => __('Enter Course ID', 'wplms'),
			),
	),
	'shortcode' => '[course id="{{id}}"]',
	'popup_title' => __('Insert Course Shortcode', 'wplms')
);

/*-----------------------------------------------------------------------------------*/
/*	PULLQUOTE Config
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['pullquote'] = array(
	'no_preview' => true,
	'params' => array(
                'style' => array(
                        'std' =>'',
			'type' => 'select',
			'label' => __('Select Side', 'wplms'),
                        'options' => array(
                            'left' => 'LEFT',
                            'right' => 'RIGHT'
                        )
			),
            'content' => array(
					'type' => 'textarea',
					'label' => __('Content', 'wplms'),	
                    ),
	),
	'shortcode' => '[pullquote style="{{style}}"]{{content}}[/pullquote]',
	'popup_title' => __('Insert PullQuote Shortcode', 'wplms')
);


/*-----------------------------------------------------------------------------------*/
/*	TEAM MEMBER Config
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['team_member'] = array(
	'no_preview' => true,
	'params' => array(
                'pic' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Member Image', 'wplms'),
			'desc' => __('Image url of team member', 'wplms'),
		),
		'name' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Member Name', 'wplms'),
			'desc' => __('Name of team member (HTML allowed)', 'wplms'),
		),
        'designation' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Designation', 'wplms'),
			'desc' => __('Designation of Team Member (HTML allowed)', 'wplms'),
		),
        ),
        'shortcode' => '[team_member pic="{{pic}}" name="{{name}}" designation="{{designation}}"] {{child_shortcode}}  [/team_member]',
        'popup_title' => __('Insert Team Member Shortcode', 'wplms'),
        'child_shortcode' => array(
        'params' => array(
                'icon' => array(
					'type' => 'socialicon',
					'label' => __('Social Icon', 'wplms'),	
                    ),
            'url' => array(
						'std' => 'http://www.vibethemes.com',
						'type' => 'text',
						'label' => __('Icon Link', 'wplms'),
                    )
                ),
        'shortcode' => '[team_social url="{{url}}" icon="{{icon}}"]',
        'clone_button' => __('Add Social Information', 'wplms')
                )
    );


/*-----------------------------------------------------------------------------------*/
/*	Google Maps Config
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['maps'] = array(
	'no_preview' => true,
	'params' => array(
		'map' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __('End Map Iframe code', 'wplms'),
			'desc' => __('Enter your map iframce code including iframe tags', 'wplms'),
		)
		
	),
	'shortcode' => '[map]{{map}}[/map]',
	'popup_title' => __('Insert Google Maps Shortcode', 'wplms')
);

/*-----------------------------------------------------------------------------------*/
/*	Gallery Config
/*-----------------------------------------------------------------------------------*/

                        
$vibe_shortcodes['gallery'] = array(
	'no_preview' => true,
	'params' => array(
                
		'size' => array(
		                'std' =>'',
			'type' => 'select',
			'label' => __('Select Thumb Size', 'wplms'),
			'desc' => __('Image size', 'wplms'),
			'options' => array(
			                        '' => 'Select Size',
			                        'normal' => 'Normal',
			                        'small' => 'Small',
			                        'micro' => 'Very Small',
			                        'large' => 'Large'
			            )
		),
		
                'ids' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Attachment Ids', 'wplms'),
			'desc' => __('Attachment Ids separated by comma', 'wplms'),
		)
		
	),
	'shortcode' => '[gallery size="{{size}}" ids="{{ids}}"]',
	'popup_title' => __('Insert Gallery Shortcode', 'wplms')
);

/*-----------------------------------------------------------------------------------*/
/*	Social Icons
/*-----------------------------------------------------------------------------------*/


$vibe_shortcodes['socialicons'] = array(
	'no_preview' => true,
	'params' => array(
		'icon' => array(
					'type' => 'socialicon',
					'label' => __('Social Icon', 'wplms'),
					'desc' => __('Select Elastic Social Icon, takes size/color of text it is inserted in:', 'wplms'),
				),	
				'size' => array(
					'std' => '32',
					'type' => 'text',
					'label' => __('Size in pixels', 'wplms'),
					'desc' => __('Enter Elastic font size in pixels ', 'wplms'),
				),
				),
				
				        'shortcode' => '[socialicon icon="{{icon}}" size="{{size}}"]',
				        'popup_title' => __('Insert Social Icon Shortcode', 'wplms')
			);
/*-----------------------------------------------------------------------------------*/
/*	Forms
/*-----------------------------------------------------------------------------------*/



$vibe_shortcodes['forms'] = array(
	'no_preview' => true,
	'params' => array(
					'forms' => array(
						'std' => __('Contact Form','wplms'),
						'type' => 'conditional',
						'label' => __('Select Form Type','wplms'),
						'desc' => '',
						'options' => array(
                            '' => 'Contact Form',
                            'event' => 'Event Form',
                        ),
                        'condition'=>array(
                        	''=>array(
                        		'to'=>'vibe_show',
                        		'subject'=>'vibe_show',
                        		'event'=>'vibe_hide'
                    		),
                        	'event'=>array(
                        		'to'=>'vibe_hide',
                        		'subject'=>'vibe_hide',
                        		'event'=>'vibe_show'
                    		),
                    	),
					),
                    'to' => array(
						'std' => 'example@example.com',
						'type' => 'text',
						'label' => __('Enter email', 'wplms'),
						'desc' => __('Email is sent to this email. Use comma for multiple entries', 'wplms'),
					),
                    'subject' => array(
						'std' => 'Subject',
						'type' => 'text',
						'label' => __('Email Subject', 'wplms'),
						'desc' => __('Subject of email', 'wplms'),
					),
					'event' => array(
						'std' => '',
						'type' => 'text',
						'label' => __('Enter custom event trigger', 'wplms'),
						'desc' => __('This event is triggerred when this form is submitted', 'wplms'),
					),
				),
	'shortcode' => '[form to="{{to}}" subject="{{subject}}" event="{{event}}"] {{child_shortcode}}  [/form]',
    'popup_title' => __('Generate Contact Form Shortcode', 'wplms'),
    'child_shortcode' => array(
        'params' => array(
                    'placeholder' => array(
						'std' => 'Name',
						'type' => 'text',
						'label' => __('Label Text', 'wplms'),
						'desc' => __('Add the content. Accepts HTML & other Shortcodes.', 'wplms'),
                    ),
                    'type' => array(
						'type' => 'select',
						'label' => __('Form Element', 'wplms'),
						'desc' => __('select Form element type', 'wplms'),
						'options' => array(
                            'text' => 'Single Line Text Box (Text)',
                            'textarea' => 'Multi Line Text Box (TextArea)',
                            'select' => 'Select from Options (Select)',
                            'checkbox' => 'Checkbox',
                            'captcha' => 'Captcha field',
                            'upload' => 'Upload File',
                            'submit' => 'Submit Button'
                        )
                    ),
		            'options' => array(
						'std' => '',
						'type' => 'text',
						'label' => __('Enter Select Options', 'wplms'),
						'desc' => __('Comma seperated options.', 'wplms'),
		            ),
		            'upload_options' => array(
						'std' => '',
						'type' => 'multiselect',
						'label' => __('Select File Extensions', 'wplms'),
						'desc' => __('select file extensions for upload type.', 'wplms'),
						'options' => array(
                            'PDF' => 'PDF',
                            'TEXT' => 'TEXT',
                            'DOC' => 'DOC',
                            'DOCx' => 'DOCX',
                            'PPT' => 'PPT',
                            'PPTX' => 'PPTX',
                            'ZIP' => 'ZIP',
                            'PNG' => 'PNG',
                            'JPG' => 'JPG',
                            'JPEG' => 'JPEG'
                        ),
		            ),
		            'validate' => array(
						'type' => 'select',
						'label' => __('Validation', 'wplms'),
						'desc' => __('select Form element type', 'wplms'),
						'options' => array(
	                            '' => 'None',
	                            'required' => 'Required',
	                            'email' => 'Email',
	                            'numeric' => 'Numeric',
	                            'phone' => 'Phone Number'
	                        )
                    ),
                    
              ),
        'shortcode' => '[form_element type="{{type}}" validate="{{validate}}" options="{{options}}" upload_options="{{upload_options}}" placeholder="{{placeholder}}"]',
        'clone_button' => __('Add Form Element', 'wplms')
    )
);	


/*-----------------------------------------------------------------------------------*/
/*	HEADING
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['heading'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __('Enter Heading', 'wplms'),
			'desc' => __('Enter heading.', 'wplms')
                    )
		),
	'shortcode' => '[heading] {{content}} [/heading]',
	'popup_title' => __('Insert Heading Shortcode', 'wplms')
);					

/*-----------------------------------------------------------------------------------*/
/*	VIDEO
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['iframevideo'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __('Enter Video iframe Code', 'wplms'),
			'desc' => __('For Responsive iframe videos form Youtube, Vimeo,bliptv etc...', 'wplms')
                    )
		),
	'shortcode' => '[iframevideo] {{content}} [/iframevideo]',
	'popup_title' => __('Insert iFrame Video Shortcode', 'wplms')
);					

/*-----------------------------------------------------------------------------------*/
/*	IFRAME
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['iframe'] = array(
	'no_preview' => true,
	'params' => array(
		'height' => array(
					'std' => '600',
					'type' => 'text',
					'label' => __('Enter Iframe Height', 'wplms'),
					'desc' => __('Set iframe height', 'wplms'),
				),
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __('Enter iframe URL', 'wplms'),
			'desc' => __('For Responsive iframe based content, like Articulate storyline, iSpring content etc...', 'wplms')
                    )
		),
	'shortcode' => '[iframe height={{height}}] {{content}} [/iframe]',
	'popup_title' => __('Insert iFrame Shortcode', 'wplms')
);	

/*-----------------------------------------------------------------------------------*/
/*	SURVEY RESULT
/*-----------------------------------------------------------------------------------*/

$vibe_shortcodes['survey_result'] = array(
	'no_preview' => true,
	'params' => array(
		'quiz_id' => array(
					'std' => '',
					'type' => 'number',
					'label' => __('Enter Quiz id (optional)', 'wplms'),
					'desc' => __('Quiz id for which Survey results are to be displayed.', 'wplms'),
				),
		'user_id' => array(
					'std' => '',
					'type' => 'number',
					'label' => __('Enter User id (optional)', 'wplms'),
					'desc' => __('User id for which Survey results are to be displayed.', 'wplms'),
				),
		'lessthan' => array(
					'std' => '',
					'type' => 'number',
					'label' => __('Enter result Upper limit ', 'wplms'),
					'desc' => __('Message to be displayed if survey score is less than this value', 'wplms'),
				),
		'greaterthan' => array(
					'std' => '',
					'type' => 'number',
					'label' => __('Enter result Lower limit ', 'wplms'),
					'desc' => __('Message to be displayed if survey score is more than this value', 'wplms'),
				),
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __('Enter Survey result message', 'wplms'),
			'desc' => __('Enter message for result', 'wplms')
                    )
		),
	'shortcode' => '[survey_result user_id={{user_id}} quiz_id={{quiz_id}} lessthan={{lessthan}} greaterthan={{greaterthan}}] {{content}} [/survey_result]',
	'popup_title' => __('Insert Survey Result Shortcode in Quiz completion message', 'wplms')
);	

?>