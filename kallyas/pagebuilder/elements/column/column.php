<?php if(! defined('ABSPATH')){ return; }
/*
	Name: Column
	Description: This element will generate a column in which you can add elements
	Class: ZnColumn
	Category: Layout
	Level: 2
	Flexible: true
	Style: true
*/

	class ZnColumn extends ZnElements {

	function options() {

		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(
					array(
						'id'          => 'column_offset',
						'name'        => 'Column offset',
						'description' => 'Here you can define an offset for this column',
						'type'        => 'select',
						'options'        => array(
								'' => 'No offset',
								'col-sm-offset-1' => '1 offset',
								'col-sm-offset-2' => '2 offset',
								'col-sm-offset-3' => '3 offset',
								'col-sm-offset-4' => '4 offset',
								'col-sm-offset-5' => '5 offset',
								'col-sm-offset-6' => '6 offset',
								'col-sm-offset-7' => '7 offset',
								'col-sm-offset-8' => '8 offset',
								'col-sm-offset-9' => '9 offset',
								'col-sm-offset-10' => '10 offset',
								'col-sm-offset-11' => '11 offset'
							),
						'live' => array(
							'type'		=> 'class',
							'css_class' => '.zn_pb_el_container[data-uid="'.$this->data['uid'].'"]'
						)
					),
					array(
						'id'          => 'size_large',
						'name'        => 'Control Size on Large screens',
						'description' => 'Select a size for this column on large devices (Desktops)(>= 1200px). By default the md/sm column sizes are applied to large screens too, this option only forces another column width on large screens.',
						'type'        => 'select',
						'std'        => '',
						'options'        => array(
								'' => 'Default',
								'col-lg-1' => '1/12',
								'col-lg-2' => '2/12',
								'col-lg-3' => '3/12',
								'col-lg-4' => '4/12',
								'col-lg-5' => '5/12',
								'col-lg-6' => '6/12',
								'col-lg-7' => '7/12',
								'col-lg-8' => '8/12',
								'col-lg-9' => '9/12',
								'col-lg-10' => '10/12',
								'col-lg-11' => '11/12',
								'col-lg-12' => '12/12',
								'col-lg-1-5' => '1/5',
							)
					),
					array(
						'id'          => 'size_small',
						'name'        => 'Size on small screens',
						'description' => 'Select a size for this column on small devices (Tablets)(>= 768px)',
						'type'        => 'select',
						'options'        => array(
								'' => 'Default',
								'col-sm-1' => '1/12',
								'col-sm-2' => '2/12',
								'col-sm-3' => '3/12',
								'col-sm-4' => '4/12',
								'col-sm-5' => '5/12',
								'col-sm-6' => '6/12',
								'col-sm-7' => '7/12',
								'col-sm-8' => '8/12',
								'col-sm-9' => '9/12',
								'col-sm-10' => '10/12',
								'col-sm-11' => '11/12',
								'col-sm-12' => '12/12',
								'col-sm-1-5' => '1/5',
							)
					),
					array(
						'id'          => 'size_xsmall',
						'name'        => 'Size on extra small screens',
						'description' => 'Select a size for this column on extra small devices (Phones)(<768px)',
						'type'        => 'select',
						'options'        => array(
								'' => 'Default',
								'col-xs-1' => '1/12',
								'col-xs-2' => '2/12',
								'col-xs-3' => '3/12',
								'col-xs-4' => '4/12',
								'col-xs-5' => '5/12',
								'col-xs-6' => '6/12',
								'col-xs-7' => '7/12',
								'col-xs-8' => '8/12',
								'col-xs-9' => '9/12',
								'col-xs-10' => '10/12',
								'col-xs-11' => '11/12',
								'col-xs-12' => '12/12',
								'col-xs-1-5' => '1/5',
							)
					),
				),
			),

			'help' => znpb_get_helptab( array(
				'video'   => 'http://support.hogash.com/kallyas-videos/#hBPFBT437_M',
				'docs'    => 'http://support.hogash.com/documentation/column/',
				'copy'    => $uid,
				'general' => true,
			)),

		);

		return $options;

	}

	function element() {

		$options = $this->data['options'];

		$column_offset = ( $this->opt('column_offset') && !ZN()->pagebuilder->is_active_editor ) ? ' '.$this->opt('column_offset').' ' : '';
		$width = ( $this->data['width'] ) ? $this->data['width'] : 'col-md-12';
		$size_small = $this->opt('size_small', str_replace("md","sm",$width));
		$size_xsmall = $this->opt('size_xsmall','');
		$size_large = $this->opt('size_large','');

		$classes=array();
		$classes[] = $this->data['uid'];
		$classes[] = zn_get_element_classes($options);
		$classes[] = $column_offset;
		$classes[] = $width;
		$classes[] = $size_small;
		$classes[] = $size_xsmall;
		$classes[] = $size_large;

		$attributes = zn_get_element_attributes($options);
	?>

		<div class="<?php echo implode(' ', $classes); ?> zn_sortable_content zn_content " data-droplevel="2" <?php echo $attributes; ?>>
			<?php ZN()->pagebuilder->zn_render_content( $this->data['content'] ); ?>
		</div>
	<?php
	}

}

?>
