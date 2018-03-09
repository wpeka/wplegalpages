
	
	function MakeStartEndPicker(startElement, endElement)
	{ 
		jQuery(startElement).datepicker({
			minDate: 0, 
			dateFormat: 'dd-mm-yy', 
			buttonText: '', 
			buttonImageOnly: true, showAnim: 'slideDown',
			duration: 0 ,
			beforeShow: function(input)
			{
			var date2 =  jQuery(endElement).datepicker('getDate');
			if(date2 != undefined) return { maxDate: date2 };
			}
		});

		jQuery(endElement).datepicker({
			minDate: 0, 
			dateFormat: 'dd-mm-yy',
			buttonText: '',
			buttonImageOnly: true, showAnim: 'slideDown',
			duration: 0 ,
			beforeShow: function(input)
			{
			var date1 =  jQuery(startElement).datepicker('getDate');
			if(date1 != undefined) return { minDate: date1 };
			}
		});
}
