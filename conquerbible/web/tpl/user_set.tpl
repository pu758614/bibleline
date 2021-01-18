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
<<<<<<< HEAD
                                <!-- <li><input type="button" style="background-color:#e0436b;" onclick="user_reset()" value="進度重置" /></li> -->
=======
                                
                                <li>
                                    <span onclick="unlock_rest()" id='lock' style="font-size: 0.2rem; color:#f33047;float:left;" class="icon solid lock_bt featured fa-lock fa-xs fa-align-left">
                                    </span>
                                    <span style='color:#000000;float:left;'> &nbsp;&nbsp;重製前請先解鎖</span>
                                    <input type="button" id="reset_bt" style="background-color:#94898b;" onclick="user_reset()" value="進度重置" disabled="disabled"/>
                                </li>
>>>>>>> ffee2b4cc1be1470e4ec8189705d9437f182b1ae
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

    function unlock_rest() {
        if(!$("#lock").hasClass('fa-lock-open')){
            $("#lock").addClass('fa-lock-open');
            $("#lock_").removeClass('fa-lock');
            $("#lock").addClass('fa-lock').css("color","#51ee34");
            $("#reset_bt").addClass('fa-lock-open').css("background-color","#db5069" );
            $("#reset_bt").prop('disabled',false);
        }else{
            $("#lock").removeClass('fa-lock-open');
            $("#lock").addClass('fa-lock');
            $("#lock").addClass('fa-lock').css("color","#f33047");
            $("#reset_bt").prop('disabled',true);
            $("#reset_bt").addClass('fa-lock-open').css("background-color","#94898b" );
        }  
    }

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
        toastr.options = {
            positionClass: "toast-bottom-center",
        };
        if(year=='' || month=='' || start_date==''){
            toastr.error('資料不能為空');
           return
        }
        if(year==0 && month==0 ){
            toastr.error('使用時間不能都為0');
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
                 toastr.options = {
                     positionClass: "toast-bottom-center",
                 };
                 if(!data.error){
                    toastr.success('儲存成功');
                 }else{
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

     function user_reset(){
        var msg = "即將要重置攻略，目前進度將會清除，請確認!！";
        if (confirm(msg)==true){
            $.ajax({
            url: 'action.php?action=re_read_book',
            type: 'post',
            data: {
                source:'user_set',
            },
            dataType: 'json',
            async:false,
            success: function(data){
                toastr.options = {
                        positionClass: "toast-bottom-center",
                    };
                if(!data.error){
                    //toastr.success( '重置成功' );
                    alert('重置成功');
                    window.location.reload()
                }else{
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
        }else{
            return false;
        }
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