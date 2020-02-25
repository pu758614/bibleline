<article id="portfolio" class="wrapper style3">
    <div class="container">
        <header>
            <h2>個人設定</h2>
            <p></p>
        </header>
        <div class="row">
			<div class="col-12">
				<form method="post" action="#">
					<div class="row">
						<div class="col-12">
                            <h4 style="text-align:left">開始攻略日期：</h4>
							<input type="text" class="use_time" style="width:35%" name="name" id="start_date" value="{start_date}" readonly>
						</div>

                        <div class="col-12" style="text-align:left">
                            <h4 style="text-align:left">預計征服一遍使用時間：</h4>
                            <select  class="use_time form-control" style="display:inline;width:20%" id='use_year'>
                              <option value ="0">0</option>
                              <option value ="1" SELECTED>1</option>
                              <option value ="2">2</option>
                              <option value ="3">3</option>
                            </select>
                            <h4 style="display:inline;">年</h4>
                            <select  class="use_time form-control" style="display:inline;width:20%" id='use_month'>
                                <option value ="0">0</option>
                                <option value ="1">1</option>
                                <option value ="2">2</option>
                                <option value ="3">3</option>
                                <option value ="4">4</option>
                                <option value ="5">5</option>
                                <option value ="6">6</option>
                                <option value ="7">7</option>
                                <option value ="8">8</option>
                                <option value ="9">9</option>
                                <option value ="10">10</option>
                                <option value ="11">11</option>
                            </select>
                            <h4 style="display:inline;">個月</h4>
                            <h4 style="display:inline;">
                                (大約一天進攻
                                <font id='read_count'> </font>
                                章)
                            </h4>
                        </div>

						<div class="col-12">
							<ul class="actions">
								<li><input type="button" onclick="save()" value="儲存" /></li>
							</ul>
						</div>
					</div>
				</form>
			</div>
        </div>
    </div>
</article>
<style>

div.ui-datepicker{
 font-size:18px;
}
</style>
<script>
    change_count()
    set_use_time();

    function set_use_time(){
        var month = '{month}';
        var year = '{year}';
        $("#use_month").val(month);
        $("#use_year").val(year);
    }
     $.datepicker.regional['zh-TW']={
        dayNames:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],
        dayNamesMin:["日","一","二","三","四","五","六"],
        monthNames:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
        monthNamesShort:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
        prevText:"上月",
        nextText:"次月",
        weekHeader:"週"
     };
     //將預設語系設定為中文
     $.datepicker.setDefaults($.datepicker.regional["zh-TW"]);

     function save(){

        var year = $("#use_year").val();
        var month = $("#use_month").val();start_date
        var start_date = $("#start_date").val();
        if(year=='' || month=='' || start_date==''){
            toastr.options = {
                positionClass: "toast-bottom-center",
            };
            toastr.error('資料不能為空');
           return
        }
        if(year==0 && month==0 ){
            toastr.options = {
                positionClass: "toast-bottom-center",
            };
            toastr.error('設定時間不能都為0');
           return
        }
         $.ajax({
             url: 'action.php?action=save_user_set',
             type: 'post',
             dataType: 'json',
             async:false,
             data: {
                year : year,
                month : month,
                start_date : start_date
             },
             success: function(data){
                 if(!data.error){
                    toastr.options = {
                        positionClass: "toast-bottom-center",
                    };
                    toastr.success('儲存成功');
                 }else{
                     toastr.options = {
                         positionClass: "toast-bottom-center",
                     };
                     toastr.error( data.msg );
                 }
             },
             error: function(data){
                 toastr.options = {
                     positionClass: "toast-bottom-center",
                 };
                 toastr.error( 'error');
             }
         });
     }

     $( "#start_date" ).datepicker({
         showMonthAfterYear : true,
         dateFormat : "yy/mm/dd",
         maxDate : "+0d"
     });


     function change_count(){
         var start_time = $("#start_date").val();
         date = new Date(start_time);
         var year = $("#use_year").val();

         year = parseInt(year);
         var month = $("#use_month").val();
         if(year==0 && month==0){
             toastr.options = {
                 positionClass: "toast-bottom-center",
             };
             toastr.error( '使用時間不能都為0' );
             $("#read_count").html('');
             return;
         }
         month = parseInt(month);
         year_m = year*12;
         total_m = year_m+month;
         date.setMonth(date.getMonth() + total_m);
         new_month = date.getMonth()+1;

         end_date = date.getFullYear()+"/"+new_month+"/"+date.getDate();
         var startdate=new Date(start_time);
         var enddate=new Date(end_date);
         var time=enddate.getTime()-startdate.getTime();
         var days=parseInt(time/(1000 * 60 * 60 * 24));
         count = 1189/days
         count = Math.ceil(count);
         $("#read_count").html(count);
     }

     $(".use_time").change(function(){
        change_count()
     });
</script>