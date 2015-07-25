<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class mezunGlobalBlock {
	
	static function normalblock(&$block) {
		
		if ($block->showtitle) {
			?>
			<div class="panel-heading"><?php echo $block->title;?></div>
			<?php
		}
		?>
		<div class="panel-body">
		<?php
		if (file_exists(ABSPATH.'/site/blocks/'.$block->block.'.php')) {
		include(ABSPATH.'/site/blocks/'.$block->block.'.php');
		}
		?>
		</div>
		<?php
	}
	
	static function htmlblock($block) {
		
		if ($block->showtitle) {
			?>
			<div class="panel-heading"><?php echo $block->title;?></div>
			<?php
		}
		?>
		<div class="panel-body">
		<?php
		echo $block->content;
		?>
		</div>
		<?php
	}
}
