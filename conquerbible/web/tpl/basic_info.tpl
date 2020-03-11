<style>
    input[type=checkbox]:not(old){
        width   : 28px;
        margin  : 0;
        padding : 0;
        opacity : 0;
    }
    input[type=checkbox]:not(old) + label{
  display      : inline-block;
  margin-left  : -28px;
  padding-left : 28px;
  line-height  : 24px;
  background   : url('images/checks.png') no-repeat 0 -48px;
}

input[type=checkbox]:not(old):checked + label{
  background-position : 0 -73px;
}
</style>
<article id="top" class="wrapper style1">
    <div class="container">
        <div class="row">
            <div class="col-4 col-5-large col-12-medium">
                <span class="image fit"><img src="images/1582214401170.jpg" alt="" /></span>
            </div>
            <div class="col-8 col-7-large col-12-medium">
                <header>
                    <h1>Hi！<strong>{user_name}</strong></h1>
                </header>
                <div  style='text-align:left;margin-left:30%;'>
                    <h3>
                        <p><strong>開始攻略日期：</strong>{start_date}</p>
                        <p><strong>舊約攻略進度：</strong><font id="old_percent">{old_percent}</font>%</p>
                        <p><strong>新約攻略進度：</strong><font id="new_percent">{new_percent}</font>%</p>
                        <p><strong>白波攻略進度：</strong><font id="all_percen">{all_percen}</font>%</p>
                        <p><strong>現在目標進度：</strong><font id="all_percen">{days_percen}</font>%</p>
                        <p><strong>完整征服次數：</strong><font id="all_percen">{done_count}</font></p>
                    </h3>
                </div>
            </div>
            <!-- <font>
                並且知道你是從小明白聖經，這聖經能使你因信基督耶穌有得救的智慧。聖經都是神所默示的，於教訓、督責、使人歸正、教導人學義，都是有益的。
                <br>(提摩太後書三章14-16節)
            </font> -->
        </div>
    </div>
</article>

<article id="work" class="wrapper style2">
    <div class="container">
        <header>
            <h2>攻略進度</h2>
            <p>點選圖示選擇顯示範圍</p>
        </header>
        <div class="row aln-cent">
            <div class="col-4 col-6-medium col-4-small" onclick="show_book('1')">
                <section class=" style1">
                    <span  class="icon solid featured fa-bible fa-xs" id='nwe_book' style='color:#EA8A95;'></span>
                    <h3>新約</h3>
                </section>
            </div>
            <div class="col-4 col-6-medium col-4-small" onclick="show_book('all')">
                <section class=" style1">
                    <span class="icon solid featured fa-book-open fa-xs fa-align-left" id='all_book' style='color:#60b5d5;'></span>
                    <h3 >全部</h3>
                </section>
            </div>
            <div class="col-4 col-6-medium col-4-small" onclick="show_book('0')">
                <section class=" style1">
                    <span class="icon solid featured fa-bible fa-xs" id='old_book' style='color:#8ee9ae;'></span>
                    <h3>舊約</h3>
                </section>
            </div>
        </div>

        <footer>
            <div  style='text-align:left;margin-left:0%;' >
                <div  >
                    <ul class="actions" style='text-align:center;'>
                        <li id='re_read'>
                            <!-- START BLOCK : reset_bt_block -->
                            <input type='button' style=background-color:#f33047 onclick='re_read()' value='重 置 攻 略 進 度 ！' /><br><br>
                            <!-- START BLOCK : reset_bt_block -->
                        </li>
                    </ul>
                </div>

                <div style="font-size: 0.5rem;" class="row col-8" >
                    <font style="color:#000000;" size="3px">*點
                    <font style="color:#f33047;" size="3px">鎖</font>
                    解除鎖定即可點擊進行進攻/撤退</font>
                    <br><br><br>

                </div>
                <!-- START BLOCK : test -->
                <div style="text-align: right;">
                    <input type="checkbox" id="show_date" name="show_date">
                    <label for="show_date" style="color:#3d2222"> 顯示已讀日期</label><br><br>
                </div>
                <!-- END BLOCK : test -->
                <!-- <p>點擊章節數可進攻/撤退</p> -->
                <!-- START BLOCK : book_block -->
                <div class="bible_book {testament_type}">
                    <span onclick="unlock('{book_id}')" id='lock_{book_id}' style="font-size: 0.2rem; color:#f33047;" class="icon solid lock_bt featured fa-lock fa-xs fa-align-left">
                        <font style='color:#000000; font-size:16px;'>
                            {book_name}
                        </font>
                    </span>
                    <table border="1" style="width:{table_w}%;"  >
                        <!-- START BLOCK : row -->
                        <tr>
                            <!-- START BLOCK : chapter -->
                            <td onclick="read_book('{data}','{book_id}')" style="text-align:center;color:black;">
                                {chapter_no}<br>
                                <font id="data_{data}_check" class ="r_check" style="color:#ff0000;font-weight:bold">
                                    {check}
                                </font>

                                <font id="data_{data}_date" class="r_date" style="color:#{no_color};display:none;font-weight:bold" >
                                    {show_date}
                                </font>
                            </td>
                            <!-- END BLOCK : chapter -->
                        </tr>
                        <!-- END BLOCK : row -->
                    </table>
                </div>
                <!-- END BLOCK : book_block -->
            </div>
        </footer>
    </div>
</article>
<script>

    $( document ).ready(function() {
        var type = "{type}";
        if(type=='schedule'){
            var top = $('#work').offset().top;
            top = top+20;
            $('html,body').animate(
                { scrollTop:top },800
            );
        }
        $("#show_date").click(function(){
            if($("#show_date").prop("checked")){
                $(".r_date").show();
                $(".r_check").hide();
                show_date_log(1);
            }else{
                $(".r_date").hide();
                $(".r_check").show();
                show_date_log(1);
            }
        })
    });
    function show_date_log(type){
        $.ajax({
            url: 'action.php?action=show_date_log',
            type: 'post',
            dataType: 'json',
            //async:false,
            data: {
                type :type
            },
            success: function(data){
            },
            error: function(data){
                
            }
        });
    }
    var is_lock = '';
    function unlock(id){
        var lock = '';
        is_lock ='';
        if($("#lock_"+id).hasClass('fa-lock-open')){
            lock = 1;
            is_lock = id;
        }else{
            is_lock = id;
        }
        $(".lock_bt").removeClass('fa-lock-open');
        $(".lock_bt").addClass('fa-lock').css("color","#f33047" );
        if(lock!=1){
            $("#lock_"+id).removeClass('fa-lock');
            $("#lock_"+id).addClass('fa-lock-open').css("color","#51ee34" );
        }else{
            is_lock ='';
        }

    }

    function re_read(){
        var msg = "即將要重置攻略，請確認！";
        if (confirm(msg)==true){
            $.ajax({
            url: 'action.php?action=re_read_book',
            type: 'post',
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



    function read_book(data_str,book_id){
        if(is_lock!=book_id){
            toastr.options = {
                timeOut: '1300',
                positionClass: "toast-bottom-center",
            };
            toastr.warning( '請先解除鎖定');
            return;
        }
        $.ajax({
            url: 'action.php?action=read_book',
            type: 'post',
            dataType: 'json',
            async:false,
            data: {
                data :data_str
            },
            success: function(data){
                if(!data.error){
                    toastr.options = {
                        positionClass: "toast-bottom-center",
                        timeOut: '1000',
                    };

                    var type = data.data.type;
                    var old_percent = data.data.old_percent;
                    var new_percent = data.data.new_percent;
                    var all_percen = data.data.all_percen;
                    var is_done = data.data.is_done;
                    if(is_done){
                        var html = "<input type='button' style=background-color:#f33047 onclick='re_read()' value='重 置 攻 略 進 度 ！' /><br><br>";
                        $("#re_read").append(html);
                        var top = $('#re_read').offset().top
                        top = top+-100
						$('html,body').animate(
							{ scrollTop:top },800
						);
                        toastr.options = {
                            positionClass: "toast-bottom-center",
                            timeOut: '2000',
                        };
                        toastr.success( '！！恭喜完成攻略！！' );
                    }else{
                        $("#re_read").html('');
                        toastr.success( data.msg );
                    }
                    if(type=='add'){
                        var date = data.data.date;
                        var day_color = data.data.day_color;
                        $("#data_"+data_str+"_check").html("✔")
                        $("#data_"+data_str+"_date").html(date);
                        $("#data_"+data_str+"_date").css("color","#"+day_color);
                    }else{
                        $("#data_"+data_str+"_check").html("");
                        $("#data_"+data_str+"_date").html('');
                        $("#data_"+data_str+"_date").css("color","");
                    }
                    $("#old_percent").html(old_percent);
                    $("#new_percent").html(new_percent);
                    $("#all_percen").html(all_percen);
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

    function show_book(type){
        $(".icon").removeClass("fa-bible");
        $(".icon").removeClass("fa-book-open");
        if(type=='0'){
            $(".bible_book").hide({
                duration: 1000,
            });
            $("#nwe_book").addClass("fa-bible");
            $("#old_book").addClass("fa-book-open");
            $("#all_book").addClass("fa-bible");
            $(".testament_0").show({
                duration: 1000,
            });
        }else if(type=='1'){
            $(".bible_book").hide({
                duration: 500,
            });
            $("#nwe_book").addClass("fa-book-open");
            $("#old_book").addClass("fa-bible");
            $("#all_book").addClass("fa-bible");
            $(".testament_1").show({
                duration: 500,
            });
        }else {
            $("#nwe_book").addClass("fa-bible");
            $("#old_book").addClass("fa-bible");
            $("#all_book").addClass("fa-book-open");
            $(".bible_book").show({
                duration: 500,
            });
        }
    }
</script>