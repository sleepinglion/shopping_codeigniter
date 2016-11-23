<section id="sl_blog_comment_new" class="box">
	<div class="box_header">
		<h2>답변 쓰기</h2>
		<div class="box_icon">
			<a class="btn_minimize" href="#"><i class="glyphicon glyphicon-chevron-up"></i></a>
			<a class="btn_close" href="#"><i class="glyphicon glyphicon-remove"></i></a>
		</div>
	</div>
	<div class="box_content" style="display: block;">
		<form method="post" id="new_question_comment" class="new_question_comment" action="/questions/<?php echo $this->uri->segment(2); ?>/comments" accept-charset="UTF-8">
			<div style="margin:0;padding:0;display:inline">
				<input type="hidden" value="✓" name="utf8">
				<input type="hidden" value="YXHHxkDIFi5KI4hK5fqHiK+8NyXHrWbkuKn/4WfqhN4=" name="authenticity_token">
			</div>
			<div class="form-group">
				<label for="blog_comment_content">내용</label>
				<br>
				<textarea required="required" name="content" id="blog_comment_content" class="form-control"></textarea>
			</div>
			<div class="form-group">
				<div>
					<script defer="" async="" src="//www.google.com/recaptcha/api.js"></script>
					<div data-sitekey="6Lf8nwITAAAAADALipm36BoeKdpYRVetB3vTBlKA" class="g-recaptcha">
						<div>
							<div style="width: 304px; height: 78px;">
								<iframe width="304" height="78" frameborder="0" src="https://www.google.com/recaptcha/api2/anchor?k=6Lf8nwITAAAAADALipm36BoeKdpYRVetB3vTBlKA&amp;co=aHR0cDovL2xvY2FsaG9zdDoyMDAwMg..&amp;hl=ko&amp;v=r20151214133724&amp;size=normal&amp;cb=rdahicba46lf" title="reCAPTCHA 위젯" role="presentation" scrolling="no"></iframe>
							</div>
							<textarea style="width: 250px; height: 40px; border: 1px solid #c1c1c1; margin: 10px 25px; padding: 0px; resize: none;  display: none; " class="g-recaptcha-response" name="g-recaptcha-response" id="g-recaptcha-response"></textarea>
							</div>
					</div>
					<noscript>
						&lt;div style="width: 302px; height: 352px;"&gt;  &lt;div style="width: 302px; height: 352px; position: relative;"&gt;    &lt;div style="width: 302px; height: 352px; position: absolute;"&gt;      &lt;iframe src="//www.google.com/recaptcha/api/fallback?k=6Lf8nwITAAAAADALipm36BoeKdpYRVetB3vTBlKA"                frameborder="0" scrolling="no"                style="width: 302px; height:352px; border-style: none;"&gt;        &lt;/iframe&gt;      &lt;/div&gt;      &lt;div style="width: 250px; height: 80px; position: absolute; border-style: none;              bottom: 21px; left: 25px; margin: 0px; padding: 0px; right: 25px;"&gt;        &lt;textarea id="g-recaptcha-response" name="g-recaptcha-response"                   class="g-recaptcha-response"                   style="width: 250px; height: 80px; border: 1px solid #c1c1c1;                   margin: 0px; padding: 0px; resize: none;" value=""&gt;         &lt;/textarea&gt;      &lt;/div&gt;    &lt;/div&gt;  &lt;/div&gt;
					</noscript>
				</div>
			</div>
			<div class="form-group actions">
				<input type="submit" value="등록" name="commit" class="btn btn-primary" />
			</div>
		</form>
	</div>
</section>