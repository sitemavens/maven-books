var REMOVE_RULE_LABEL = 'X';
var BOX_REMOVE_RULE_TEXT = 'Are you sure you want to remove this?';

function jquerync(){
	return jQuery.noConflict();
}
(function($) {
	function Mvn_Shop_ProductCategory(){
		this.init = function(){
			
		};
		
		/**
		 * Add new field
		 */
		this.addSmartRule = function(rid) {
			if(rid == undefined)
				rid = this.getNextSmartRuleId();
			
			var jq = jquerync();
			var html = '';
			
			html += '		<li id="mvn-shop-smart-rule-'+rid+'" class="mvn-shop-smart-rule">';
			html += '			<label><button id="remove-smart-rule-'+rid+'" class="button-secondary remove_smart_rule">'+REMOVE_RULE_LABEL+'</button> '+productCategoryObj.ruleLabel+'</label>';
			html += '			'+productCategoryObj.field.replace(/-rid-/g, rid);
			html += '			'+productCategoryObj.operator.replace(/-rid-/g, rid);
			html += '			<input type="text" value="" name="mvn_shop_term[smart_rules]['+rid+'][rule]" />';
			html += '		</li>';
			
			jq(html).appendTo('#mvn-shop-category-smart-rules ul');
			
			return rid;
		};
		
		/**
		 * Remove field
		 */
		this.removeSmartRule = function(rid) {
			if(rid == undefined)
				return false;
			var jq = jquerync();
			jq('#mvn-shop-smart-rule-' + rid).remove();
			
			// if fid is empty it could be a new field removed
			// Delete it and return true;
//			if(!fid) {
//				jq('#mvn-field-' + rid).remove();
//				return true;
//			}
			
			/* Send the data using post and put the results in a span */
//			jq.post( ajaxurl, {
//					action: 'mvn_shop_smart_rule_remove',
//					row_id: rid
//				},
//				function( data ) {
//					if( data != undefined && data.is_error != undefined && ! data.is_error )
//					{
//						jq('#mvn-shop-smart-rule-' + rid).remove();
//						jq('#message-container').attr('class', 'updated');
//					}else
//					{
//						jq('#message-container').attr('class', 'error');
//					}
//					jq('#mvn-shop-taxonomy-field-message').html(data.message);
//					jq('#message-container').show();
//				}
//			);
//			return false;
		};
		
		/**
		 * Get next id for next field
		 */
		this.getNextSmartRuleId = function() {			
			var maxRuleId = 0;
			var rule = jQuery('.mvn-shop-smart-rule');
			if(rule.length > 0) {
				rule.each(function () {
					var val = parseInt(jQuery(this).attr('id').replace('mvn-shop-smart-rule-', ''));
						if(maxRuleId <= val)
							maxRuleId = val + 1;
				});
			}
			if(maxRuleId < 0)
				maxRuleId = 0;
			return maxRuleId;
		};
	}

	jQuery(document).ready(function(){
		var mvnProductCategory = new Mvn_Shop_ProductCategory();
		var jq = jquerync();
	
		jq('button.add_smart_rule').live('click', function(e){
			e.preventDefault();
			var fieldId = mvnProductCategory.addSmartRule();
		});

		jq('button.remove_smart_rule').live('click', function(e){
			var answer = confirm(BOX_REMOVE_RULE_TEXT);
			if (answer){
				var rowId = jq(this).attr('id').replace('remove-smart-rule-', '');
				mvnProductCategory.removeSmartRule(rowId);
			}
			return false;
		});
		jq('#mvn_shop_is_smart_term').live('click', function(e){
			if(jq(this).is(':checked')) {
				jq('#mvn-shop-category-smart-rules').show();
			}else{
				jq('#mvn-shop-category-smart-rules').hide();
			}
		});
		
	});
	
})(jQuery);