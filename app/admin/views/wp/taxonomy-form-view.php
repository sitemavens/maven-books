<table class="form-table">
	<tr class="form-field">
		<td>
			<?php $lang->_e('Is Smart Category'); ?>
		</td>
		<td>
			<input type="hidden" name="mvn_shop_is_smart_term" value="0" />
			<input type="checkbox" <?php checked("1", $is_smart_term); ?> name="mvn_shop_is_smart_term" id="mvn_shop_is_smart_term" value="1" />
		</td>
	</tr>
	<tr class="form-field">
		<td colspan="2">
			<div class="mvn-shop-category-form-container clear clearfix">
				<div id="mvn-shop-category-smart-rules" style="display:<?php echo ($is_smart_term)?'block':'none'?>;">
					<button class="button-secondary add_smart_rule" name="mvn_shop_add_smart_rule" id="mvn_shop_add_smart_rule"><?php $lang->_e('Add Rule'); ?></button>
					<select name="mvn_shop_term[smart_operator]">
						<option value="AND" <?php echo ($smart_operator == "AND")? 'selected="selected"': '';?> >AND</option>
						<option value="OR" <?php echo ($smart_operator == "OR")? 'selected="selected"': '';?> >OR</option>
					</select>
					<ul>
				<?php if ( $smart_term_rules ): ?>
					<?php foreach ($smart_term_rules as $key => $smart_rule): ?>
						<li id="mvn-shop-smart-rule-<?php echo $key;?>" class="mvn-shop-smart-rule">
							<label><button id="remove-smart-rule-<?php echo $key;?>" class="button-secondary remove_smart_rule">X</button> <?php $lang->_e('Rule'); ?></label>
							<?php Mvn_Shop_Product_Taxonomies::dropdown_fields(array('selected' =>$smart_rule['field'], 'name' => "mvn_shop_term[smart_rules][{$key}][field]", 'id' => "mvn-shop-category-smart-field-{$key}")); ?>
							<?php Mvn_Shop_Product_Taxonomies::dropdown_operators(array('selected' =>$smart_rule['operator'], 'name' => "mvn_shop_term[smart_rules][{$key}][operator]", 'id' => "mvn-shop-category-smart-operator-{$key}")); ?>
							<input type="text" value="<?php echo esc_attr($smart_rule['rule']); ?>" name="mvn_shop_term[smart_rules][<?php echo $key;?>][rule]" />
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
					</ul>
				</div>
			</div>
	    </td>
	</tr>
</table>