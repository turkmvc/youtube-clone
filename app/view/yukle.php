	<div class="container">
		<?php include view('static/sidebar'); ?>
		<div class="site icerik">
			<div class="siteic">
				<div class="sabitsayfa">
					<div class="kutu">
						<form class="yukle" method="post" action="<?php echo base_url('yukle'); ?>" enctype="multipart/form-data">

							<?php if(isset($hata)): ?>
							<div class="hata">
								<i class="fa fa-exclamation-triangle"></i>
								<?php echo $hata; ?>
							</div>
							<?php endif; ?>

							<?php if(isset($basari)): ?>
							<div class="basari">
								<i class="fa fa-check-circle"></i>
								<?php echo $basari; ?>
							</div>
							<?php endif; ?>
							
							<div class="grup">
								<label>Video Başlığı:</label>
								<input type="text" name="baslik" placeholder="Video başlığı (Maksimum 400 karakter)" required>
							</div>

							<div class="grup">
								<label>Video Kategorisi:</label>
								<select name="kategori">
									<option value="0">Kategori seçiniz</option>
									<?php
										$kategoriler = $db->query("SELECT * FROM kategoriler", PDO::FETCH_ASSOC);
										if($kategoriler->rowCount()){
											foreach ($kategoriler as $kategori) {
												echo '<option value="'.$kategori['id'].'">'.$kategori['isim'].'</option>';
											}
										}
									?>
								</select>
							</div>

							<div class="grup">
								<label>Video Açıklaması:</label>
								<textarea name="aciklama" placeholder="Video açıklamasını giriniz.." rows="5" required></textarea>
							</div>

							<div class="grup">
								<label>Video: <span>(mp4, avi)</span></label>
								<input type="file" name="video" accept="video/*" required>
							</div>

							<div class="grup">
								<label>Video Görseli: <span>(625x350 - png, jpg)</span></label>
								<input type="file" name="kapak" accept="image/*" required>
							</div>		

							<div class="grup">
								<button type="submit" value="submit">Videoyu Yükle</button>	
							</div>
								

						</form><!--yukle-->
					</div><!--kutu-->
				</div><!--sabitsayfa-->
			</div><!--siteic-->
		</div><!--site-->
	</div><!--container-->