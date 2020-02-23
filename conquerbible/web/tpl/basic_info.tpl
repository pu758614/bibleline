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

                <div  style='text-align:left;margin-left:35%;'>
                    <p><strong>開始攻略日期：</strong>{start_date}</p>
                    <p><strong >舊約攻略進度：</strong><font  id="old_percent">{old_percent}</font>%</p>
                    <p><strong>新約攻略進度：</strong><font id="new_percent">{new_percent}</font>%</p>
                    <p><strong>白波攻略進度：</strong><font id="all_percen">{all_percen}</font>%</p>
                </div>
            </div>
        </div>
    </div>
</article>

<article id="work" class="wrapper style2">
    <div class="container">
        <header>
            <h2>攻略進度</h2>
            <p>可點選下圖選擇顯示範圍</p>
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
            <div  style='text-align:left;margin-left:0%;'>
                <p>點擊章節數可進攻/撤退</p>
                <!-- START BLOCK : book_block -->
                <div class="bible_book {testament_type}">
                    <strong>{book_name}</strong>
                    <table border="1" style="width:{table_w}%;"  >
                        <!-- START BLOCK : row -->
                        <tr>
                            <!-- START BLOCK : chapter -->
                            <td onclick="read_book('{data}')" style="text-align:center;">
                                {chapter_no}
                                <font id="data_{data}" style="color:red;font-weight:bold">
                                    {check}
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
    });

    function read_book(data_str){
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
                        timeOut: '500',
                    };
                    toastr.success( data.msg );
                    var type = data.data.type;
                    var old_percent = data.data.old_percent;
                    var new_percent = data.data.new_percent;
                    var all_percen = data.data.all_percen;
                    if(type=='add'){
                        $("#data_"+data_str).html("✔")
                    }else{
                        $("#data_"+data_str).html("")
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