<div class="container">
		<div class="videosayfa">
			<div class="site icerik sitevideo">
				<div class="siteic sayfavideo">

					<div class="playerdiv">
						<video id="player" playsinline controls autoplay>
						    <source src="<?php echo upload_url("video/".$vd["video"]); ?>" type="video/mp4">
						</video>
					</div><!--playerdiv-->

					<div class="videobaslik">
						<h1><?php echo htmlspecialchars(strip_tags(trim($vd["baslik"]))); ?> <span><i class="fa fa-eye"></i> <b><?php echo intval($vd["izlenme"]); ?></b> görüntülenme </span></h1>
					</div>

					<?php $kanal = kanal_bilgileri($vd["kanal"]); ?>

					<div class="videobilgi">
						<div class="kanalbilgi">
							<a href="<?php echo kanal_url($vd["kanal"]); ?>">
								<img src="<?php echo kanal_resim($kanal["id"]); ?>">
							</a>
							<div class="kanalbilgitext">
								<a href="<?php echo kanal_url($vd["kanal"]); ?>" class="kanalisim"><?php echo $kanal["kanal"]; ?> <?php if($kanal["dogrulanmis"] == 1){echo '<i title="Doğrulanmış Kanal" class="fa fa-check-circle"></i>';} ?></a>
								<span><?php echo date("d.m.Y",$vd["tarih"]); ?> tarihinde eklendi</span>
							</div>
						</div><!--kanalbilgi-->
						<div class="begenibilgi">
							<!-- a aktif class alıyor -->
							<script type="text/javascript">
								var begeni_suan = <?php echo ben_begendim_mi($vd["id"]); ?>;
								var begeni_like = <?php echo video_begeni_sayisi($vd["id"],"like"); ?>;
								var begeni_dislike = <?php echo video_begeni_sayisi($vd["id"],"dislike"); ?>;
							</script>
							<a href="javascript:void(0)" class="begenenler" id="begenenler">
								<?php

									$video_id = $vd["id"];
									$begenenler_sorgu = $db->query("SELECT * FROM begeni JOIN uyeler ON begeni.uye_id = uyeler.id WHERE begeni.video_id = {$video_id}", PDO::FETCH_ASSOC);
									if($begenenler_sorgu->rowCount()){
										$i = 0;
										$begenenler_liste = [];
										foreach ($begenenler_sorgu as $begenen) {
											if($i < 3){
												array_push($begenenler_liste, $begenen["kanal"]);
												$i++;	
											}
										}
										echo implode(", ", $begenenler_liste);
										if($begenenler_sorgu->rowCount() > $i){
											echo " ve " . ($begenenler_sorgu->rowCount() - $i) . " kişi daha";
										}
									}else{
										echo "Kimse beğenmedi";
									}


								?>
							</a>
							<a href="javascript:void(0)" onclick="begeni(<?php echo $vd['id']; ?>,1)" class="like <?php if(ben_begendim_mi($vd["id"]) == 1){echo 'likeactive';} ?>"><i class="fa fa-thumbs-up"></i> <?php echo video_begeni_sayisi($vd["id"],"like"); ?></a>
							<a href="javascript:void(0)" onclick="begeni(<?php echo $vd['id']; ?>,2)" class="dislike <?php if(ben_begendim_mi($vd["id"]) == 2){echo 'dislikeactive';} ?>"><i class="fa fa-thumbs-down"></i> <?php echo video_begeni_sayisi($vd["id"],"dislike"); ?></a>
						</div><!--begenibilgi-->
					</div><!--videobilgi-->

					<div class="clearfix"></div>

					<div class="videoaciklama">
						<div class="metin">
							<?php echo $vd["aciklama"]; ?>
						</div>
						<p class="devaminigor"><a href="javascript:void(0);">Devamını Göster</a></p>
					</div>

					<div class="clearfix"></div>

					<div class="yorumlar">

						<h3>Yorumlar (<?php echo yorum_sayisi($vd["id"]); ?>)</h3>
						
						<div class="yeniyorum">
							<div class="yorumyap" id="yorumyap">
								<label>Videoya yeni yorum ekleyin: <span></span></label>
								<textarea name="yorum" placeholder="Görüşlerinizi buraya yazın ve paylaş butonuna basın"></textarea>
								<input type="hidden" name="parent" value="0">
								<input type="hidden" name="etiket" value="0">
								<input type="hidden" name="video" value="<?php echo $vd['id']; ?>">
								<button class="paylas">Paylaş</button>
								<button class="temizle">İptal</button>
							</div>
						</div><!--yeniyorum-->

						<div class="clearfix"></div>

						<?php

						if(yorum_sayisi($vd["id"]) > 0){

							$vd_id = $vd["id"];
							$ana_yorumlar = $db->query("SELECT * FROM yorum WHERE video_id = {$vd_id} AND parent_id = 0 ORDER BY yorum_id ASC", PDO::FETCH_ASSOC);
							if($ana_yorumlar->rowCount()){
								foreach ($ana_yorumlar as $ana_yorum) {
									$anayorumid = $ana_yorum["yorum_id"];
									$yorum_yapan = kanal_bilgileri($ana_yorum["uye_id"]);
									$yorum_begeni_sayisi = yorum_begeni_sayisi($anayorumid);
									?>
									<div class="yorum">
										<div class="yorumbox">
											<div class="yorumresim">
												<a href="<?php echo kanal_url($yorum_yapan['id']); ?>"><img src="<?php echo kanal_resim($yorum_yapan['id']); ?>" width="45" height="45"></a>
											</div>
											<div class="yorumic">
												<a href="<?php echo kanal_url($yorum_yapan['id']); ?>" class="kanalisim"><?php echo $yorum_yapan["kanal"]; ?></a>
												<span><?php if(!empty($yorum_begeni_sayisi)){echo "(" . $yorum_begeni_sayisi . ")";} ?></span>
												<p><?php echo htmlspecialchars(stripslashes(strip_tags(trim($ana_yorum["yorum"])))); ?></p>
												<?php
													$begeni_kontrol = yorumu_ben_begendim_mi($anayorumid);
												?>
												<a href="javascript:void(0)" islem="1" yorumid="<?php echo $anayorumid; ?>" class="yorumbuton yorumbegeni <?php if($begeni_kontrol == 1){echo 'active';} ?>"><i class="fa fa-thumbs-up"></i></a>
												<a href="javascript:void(0)" islem="2" yorumid="<?php echo $anayorumid; ?>" class="yorumbuton yorumbegeni <?php if($begeni_kontrol == 2){echo 'active';} ?>"><i class="fa fa-thumbs-down"></i></a>
												<a href="javascript:void(0)" class="yorumbuton yorumyanitla" yorum="<?php echo $anayorumid; ?>" etiket="0" kanal="<?php echo $yorum_yapan["kanal"]; ?>">Yanıtla</a>
											</div>
										</div>
									
									<?php

									$alt_yorumlar = $db->query("SELECT * FROM yorum WHERE video_id = {$vd_id} AND parent_id = {$anayorumid}", PDO::FETCH_ASSOC);
									if($alt_yorumlar->rowCount()){
										echo '<div class="cevaplar">';
										foreach ($alt_yorumlar as $alt_yorum) {
											$altyorumid = $alt_yorum["yorum_id"];
											$yorum_yapan = kanal_bilgileri($alt_yorum["uye_id"]);
											$yorum_begeni_sayisi = yorum_begeni_sayisi($altyorumid);
											?>

											<div class="yorumbox">
												<div class="yorumresim">
													<a href="<?php echo kanal_url($yorum_yapan['id']); ?>"><img src="<?php echo kanal_resim($yorum_yapan['id']); ?>" width="45" height="45"></a>
												</div>
												<div class="yorumic">
													<a href="<?php echo kanal_url($yorum_yapan['id']); ?>" class="kanalisim"><?php echo $yorum_yapan["kanal"]; ?></a>
													<span><?php if(!empty($yorum_begeni_sayisi)){echo "(" . $yorum_begeni_sayisi . ")";} ?></span>
													<p><?php if($alt_yorum["etiket"]){ echo '<a href="'.kanal_url(kanal_bilgileri($alt_yorum["etiket"], "id")).'" class="etiket">'.kanal_bilgileri($alt_yorum["etiket"], "kanal").'</a>'; } ?><?php echo htmlspecialchars(stripslashes(strip_tags(trim($alt_yorum["yorum"])))); ?></p>
													<?php
														$begeni_kontrol = yorumu_ben_begendim_mi($altyorumid);
													?>
													<a href="javascript:void(0)" islem="1" yorumid="<?php echo $altyorumid; ?>" class="yorumbuton yorumbegeni <?php if($begeni_kontrol == 1){echo 'active';} ?>"><i class="fa fa-thumbs-up"></i></a>
													<a href="javascript:void(0)" islem="2" yorumid="<?php echo $altyorumid; ?>" class="yorumbuton yorumbegeni <?php if($begeni_kontrol == 2){echo 'active';} ?>"><i class="fa fa-thumbs-down"></i></a>
													<a href="javascript:void(0)" class="yorumbuton yorumyanitla" yorum="<?php echo $anayorumid; ?>" etiket="<?php echo $yorum_yapan["id"]; ?>" kanal="<?php echo $yorum_yapan["kanal"]; ?>">Yanıtla</a>
												</div>
											</div>

											<?php
										}
										echo '</div>'; // <div class="cevaplar">
									}


									echo '</div>'; // <div class="yorum">
								}
							}

						}else{
							echo '<p style="text-align:center;margin-top:30px;">Bu video için hiç yorum yapılmamış.</p>';
						}


						?>

					</div><!--yorumlar-->
					
				</div><!--siteic-->
			</div><!--site-->

			<div class="sidebar sidebarvideo" id="sidebar">
				<div class="sidebaric sidebaricvideo">


					<div class="otomatikOynatma">
						<label for="otoCheckbox"><input type="checkbox" id="otoCheckbox" <?php if(!empty($_COOKIE["yt_otomatik_oynatma"])){echo "checked";} ?>> Otomatik oynatma</label>
					</div>

					<?php

					$kategori = $vd["kategori"];
					$vid = $vd["id"];
					$siradaki;

					if(!empty($kategori)){
						$benzer_videolar = $db->query("SELECT * FROM video WHERE kategori = {$kategori} AND id != {$vid} ORDER BY RAND() DESC LIMIT 10", PDO::FETCH_ASSOC);
						if($benzer_videolar->rowCount()){
							foreach ($benzer_videolar as $bv) {
								$bv_url = base_url() . "video?id=" . $bv["id"];
								$bv_img = base_url() . "upload/kapak/" . $bv["kapak"];
								if(empty($siradaki)){$siradaki = $bv_url;}
								?>
								<div class="onerilen">
								
									<a href="<?php echo $bv_url; ?>" title="<?php echo $bv["baslik"]; ?>"><img src="<?php echo $bv_img; ?>"></a>
									<div class="baslik">
										<a href="<?php echo $bv_url; ?>" title="<?php echo $bv["baslik"]; ?>"><?php echo $bv["baslik"]; ?></a>
									</div>
									<p class="kanal"><a href="<?php echo kanal_url($bv["kanal"]); ?>" title="<?php echo kanal_bilgileri($vd["kanal"], "kanal"); ?>"><?php echo kanal_bilgileri($bv["kanal"], "kanal"); ?></a></p>
								</div><!--onerilen-->
								<div class="clearfix"></div>
								<?php
							}
						}
					}

					?>	

				</div><!--sidebaric-->
			</div><!--sidebar-->
		</div><!--videosayfa-->
		
	</div><!--container-->

	<div class="modal" id="modal_begenenler">
		<div class="modalic">
			<div class="modalust">
				<h3>Beğenenler Listesi</h3>
				<a class="kapat"><i class="fa fa-times"></i></a>
			</div>
			<div class="modalicerik">
				<div class="begenenler-liste">
				<?php

				$vid = $vd["id"];
				$begeni_liste = $db->query("SELECT * FROM begeni LEFT JOIN uyeler ON begeni.uye_id = uyeler.id WHERE video_id = {$vid} AND uyeler.id IS NOT NULL", PDO::FETCH_ASSOC);
				if($begeni_liste->rowCount()){
					foreach($begeni_liste as $begenen){
						?>

						<div class="begenen">
							<a class="kanalresim" href="<?php echo kanal_url($begenen["uye_id"]); ?>">
								<img src="<?php echo kanal_resim($begenen["id"]); ?>">
							</a>
							<a href="<?php echo kanal_url($begenen["uye_id"]); ?>" class="kanalisim"><?php echo $begenen["kanal"]; ?></a>
							<div class="islem">
								<?php
									if($begenen["islem"] == 1){
										echo '<i class="fa fa-thumbs-up yesil"></i>';
									}elseif($begenen["islem"] == 2){
										echo '<i class="fa fa-thumbs-down kirmizi"></i>';
									}
								?>
							</div>
						</div>

						<?php
					}
				}else{
					echo '<p>Bu videoyu henüz kimse beğenmedi.</p>';
				}

				
				?>
				</div>	
			</div>
		</div>
	</div><!--modal-->

	<script>
		<?php
		if(!empty($siradaki) && !empty($_COOKIE["yt_otomatik_oynatma"])){
			echo "var otomatik_oynatma = 1;";
			echo "var siradaki = '" . $siradaki . "';";
		}else{
			echo "var otomatik_oynatma = 0;";
			if(empty($siradaki)){
				echo "var siradaki = '" . video_url($vd["id"]) . "';";
			}else{
				echo "var siradaki = '" . $siradaki . "';";
			}
		}
		?> 
	</script>