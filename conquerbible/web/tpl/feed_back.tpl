<article id="portfolio" class="wrapper style3">
    <div class="container">
        <header>
            <h2>意見反饋</h2>
            <p>感謝您使用本系統，如果對於本系統有任何意見或是BUG，歡迎回應給我！3Q～～</p>
            <a style="color:#00d900" href="https://line.me/ti/p/RU32_w54Y-" class="icon brands fa-line fa-2x"><span class="label">LINE</span></a>←也可連絡我的LINE
            <h6><p><STRIKE>(會不會馬上處理又是另一回事了xDD)</STRIKE></p></h6>
        </header>
        <div class="row">
			<div class="col-12">
				<form method="post" action="#">
					<div class="row">
						<div class="col-12">
                            <h4 style="text-align:left">您的稱呼：</h4>
							<input type="text" name="name" id="name" placeholder="" />
						</div>
						<div class="col-12">
                            <h4 style="text-align:left">意見內容：</h4>
							<textarea name="message" id="message" placeholder=""></textarea>
						</div>
						<div class="col-12">
							<ul class="actions">
								<li><input type="button" onclick="submit_msg()" value="發送" /></li>
								<li><input type="reset" value="清空" class="alt" /></li>
							</ul>
						</div>
					</div>
				</form>
			</div>
        </div>
        <footer>
        
        </footer>
    </div>
</article>
<script>
    function submit_msg(){
        var name = $("#name").val();
        var message = $("#message").val();
        $.ajax({
            url: 'action.php?action=feed_back',
            type: 'post',
            dataType: 'json',
            async:false,
            data: {
                name : name,
                message : message,
            },
            success: function(data){
                if(!data.error){
                    alert("訊息發送成功，感謝您的支持！");
                }else{
                    alert(data.msg);
                }
            },
            error: function(data){
                // toastr.options = {
                //     positionClass: "toast-bottom-center",
                // };
                // toastr.error( 'error');
                alert(data.msg);
            }
        });
    }
</script>