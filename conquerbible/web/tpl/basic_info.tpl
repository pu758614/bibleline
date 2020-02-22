
<article id="top" class="wrapper style1">
    <div class="container">
        <div class="row">
            <div class="col-4 col-5-large col-12-medium">
                <span class="image fit"><img src="images/pic00.jpg" alt="" /></span>
            </div>
            <div class="col-8 col-7-large col-12-medium">
                <header>
                    <h1>Hi！<strong>{user_name}</strong></h1>
                </header>
                <div  style='text-align:left;margin-left:35%;'>
                    <p><strong>開始讀經日期：</strong>{start_date}</p>
                    <p><strong>舊約進度：</strong>{old_percent}%</p>
                    <p><strong>新約約進度：</strong>{new_percent}%</p>
                    <p><strong>全書進度：</strong>{all_percen}%</p>
                </div>
            </div>
        </div>
    </div>
</article>

<article id="work" class="wrapper style2">
    <div class="container">
        <header>
            <h2>讀經進度</h2>
            <p>可點選下圖選擇顯示範圍</p>
        </header>
        <div class="row aln-cent">
            <div class="col-4 col-6-medium col-4-small" onclick="show_book('1')">
                <section class="box style1">
                    <span class="icon solid featured fa-bible" style='color:#EA8A95;'></span>
                    <h3>新約</h3>
                </section>
            </div>
            <div class="col-4 col-6-medium col-4-small" onclick="show_book('all')">
                <section class="box style1">
                    <span class="icon solid featured fa-bible" style='color:#60b5d5;'></span>
                    <h3 >全部</h3>
                </section>
            </div>
            <div class="col-4 col-6-medium col-4-small" onclick="show_book('0')">
                <section class="box style1">
                    <span class="icon solid featured fa-bible" style='color:#8ee9ae;'></span>
                    <h3>舊約</h3>
                </section>
            </div>
        </div>
        <footer>
            <div  style='text-align:left;margin-left:0%;'>
                <!-- START BLOCK : book_block -->
                <div class="bible_book {testament_type}">
                    <strong>{book_name}</strong>
                    <table border="1" style="width:{table_w}%;"  >
                        <!-- START BLOCK : row -->
                        <tr>
                            <!-- START BLOCK : chapter -->
                            <td  style="text-align:center;">
                                {chapter_no}
                                <font style="color:red;font-weight:bold">
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
    function show_book(type){
        if(type=='0'){
            $(".bible_book").hide();
            $(".testament_0").show();
        }else if(type=='1'){
            $(".bible_book").hide();
            $(".testament_1").show();
        }else {
            $(".bible_book").show();
        }
    }
</script>