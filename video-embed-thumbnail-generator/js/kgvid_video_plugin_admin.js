function kgvid_disable_thumb_buttons(postID, event) {

	if ( jQuery('.compat-item').length > 0 ) { //only do this in the new media modal, not attachment page in media library
		if (event == "onblur") {
			document.getElementById('attachments-'+postID+'-thumbgenerate').disabled = false;
			document.getElementById('attachments-'+postID+'-thumbrandomize').disabled = false;
		}
		else {
			document.getElementById('attachments-'+postID+'-thumbgenerate').disabled = true;
			document.getElementById('attachments-'+postID+'-thumbrandomize').disabled = true;
		}

		if (event == "onchange") {
			document.getElementById('attachments-'+postID+'-thumbgenerate').value = kgvidL10n.wait;
			document.getElementById('attachments-'+postID+'-thumbrandomize').value = kgvidL10n.wait;
		}
	}
}

function kgvid_set_dimension(postID, valuetochange, currentvalue) {
	var kgvid_aspect = (document.getElementsByName('attachments['+postID+'][kgflashmediaplayer-aspect]')[0].value);
	var changeaspect = kgvid_aspect;
	if (valuetochange == "width") { changeaspect = 1/kgvid_aspect; }
	var changedvalue = Math.round(currentvalue*changeaspect);
	if (document.getElementById('attachments-'+postID+'-kgflashmediaplayer-lockaspect').checked == true && changedvalue != 0) {
		document.getElementById('attachments-'+postID+'-kgflashmediaplayer-'+valuetochange).value = changedvalue;
	}
}

function kgvid_set_aspect(postID, checked) {
	if (checked) { document.getElementsByName('attachments['+postID+'][kgflashmediaplayer-aspect]')[0].value = document.getElementById('attachments-'+postID+'-kgflashmediaplayer-height').value / document.getElementById('attachments-'+postID+'-kgflashmediaplayer-width').value;
	}
}

function kgvid_convert_to_timecode(time) {

	var time_display = '';

	if ( time ) {

		var minutes = Math.floor(time / 60);
		var seconds = Math.round((time - (minutes * 60))*100)/100;
		if (minutes < 10) {minutes = "0"+minutes;}
		if (seconds < 10) {seconds = "0"+seconds;}
		time_display = minutes+':'+seconds;

	}

	return time_display;

}

function kgvid_convert_from_timecode(timecode) {

	var thumbtimecode = 0;

	if ( timecode ) {

		var timecode_array = timecode.split(":");
		timecode_array = timecode_array.reverse();
		if ( timecode_array[1] ) { timecode_array[1] = timecode_array[1] * 60; }
		if ( timecode_array[2] ) { timecode_array[2] = timecode_array[2] * 3600; }

		jQuery.each(timecode_array,function() {
			thumbtimecode += parseFloat(this);
		});

	}

	return thumbtimecode;

}

function kgvid_break_video_on_close(postID) {

	var video = document.getElementById('thumb-video-'+postID);

	if ( video != null ) {

		var playButton = jQuery(".kgvid-play-pause");

		playButton.off("click.kgvid");
		video.preload = "none";
		video.src = "";
		video.load();
		jQuery(video).data('setup', false);
		jQuery(video).data('busy', false);
	}

};

function kgvid_thumb_video_loaded(postID) { //sets up mini custom player for making thumbnails

	var video = document.getElementById('thumb-video-'+postID);

	if ( video != null ) {
		var crossDomainTest = jQuery.get( video.currentSrc )
			.fail( function(){
				jQuery('#thumb-video-'+postID+'-container').hide();
				jQuery('#thumb-video-'+postID).data('allowed', 'off');
				kgvid_break_video_on_close(postID);
			});
	}

	jQuery('#attachments-'+postID+'-thumbgenerate').prop('disabled', false).attr('title', '');
	jQuery('#attachments-'+postID+'-thumbrandomize').prop('disabled', false).attr('title', '');
	jQuery('#attachments-'+postID+'-kgflashmediaplayer-numberofthumbs').prop('disabled', false).attr('title', '');

	jQuery('#thumb-video-'+postID+'-container').show();

	if ( video != null && jQuery(video).data('setup') != true ) {

		if ( typeof wp !== 'undefined' ) {
			ed_id = wp.media.editor.id();
			var ed_media = wp.media.editor.get( ed_id ); // Then we try to first get the editor
			ed_media = 'undefined' != typeof( ed_media ) ? ed_media : wp.media.editor.add( ed_id ); // If it hasn't been created yet, we create it

			if ( ed_media ) {
				ed_media.on( 'escape',
				function(postID) {
					return function() {
						if ( jQuery('#show-thumb-video-'+postID+' .kgvid-show-video').html() == kgvidL10n.hidevideo ) {
							kgvid_reveal_thumb_video(postID);
						}
						//kgvid_break_video_on_close(postID);
					}
				}(postID) );
			}
		}

		video.removeAttribute('height'); //disables changes made by mejs
		video.removeAttribute('style');
		video.setAttribute('width', '200');
		video.controls = '';

		var playButton = jQuery(".kgvid-play-pause");
		var seekBar = jQuery(".kgvid-seek-bar");
		var playProgress = jQuery(".kgvid-play-progress");
		var seekHandle = jQuery(".kgvid-seek-handle");

		playButton.on("click.kgvid", function() {
		  if (video.paused == true) {
			// Play the video
			video.play();
		  }
		  else {
			// Pause the video
			video.pause();
			video.playbackRate = 1;
		  }
		});

		video.addEventListener('play', function() {
			playButton.addClass('kgvid-playing');
		});

		video.addEventListener('pause', function() {
			playButton.removeClass('kgvid-playing');
		});

		//update HTML5 video current play time
		video.addEventListener('timeupdate', function() {
		   var currentPos = video.currentTime; //Get currenttime
		   var maxduration = video.duration; //Get video duration
		   var percentage = 100 * currentPos / maxduration; //in %
		   playProgress.css('width', percentage+'%');
		   seekHandle.css('left', percentage+'%');
		});

		var timeDrag = false;   /* Drag status */
		seekBar.on('mousedown', function(e) {
			if ( video.paused == false ) {
				video.pause();
			}

			if ( video.currentTime == 0 ) {
				video.play(); //video won't seek in Chrome unless it has played once already
			}

			timeDrag = true;
			updatebar(e.pageX);
		});
		jQuery(document).on('mouseup', function(e) {
		   if(timeDrag) {
			  timeDrag = false;
			  updatebar(e.pageX);
		   }
		});
		jQuery(document).on('mousemove', function(e) {
		   if(timeDrag) {
			  updatebar(e.pageX);
		   }
		});
		//update Progress Bar control
		var updatebar = function(x) {
		   var maxduration = video.duration; //Video duraiton
		   var position = x - seekBar.offset().left; //Click pos
		   var percentage = 100 * position / seekBar.width();
		   //Check within range
		   if(percentage > 100) {
			  percentage = 100;
		   }
		   if(percentage < 0) {
			  percentage = 0;
		   }
		   //Update progress bar and video currenttime
		   playProgress.css('width', percentage+'%');
		   seekHandle.css('left', percentage+'%');
		   video.currentTime = maxduration * percentage / 100;

		};

		jQuery(video).on('loadedmetadata', function() {
			var currentTimecode = jQuery('#attachments-'+postID+'-kgflashmediaplayer-thumbtime').val();
			if ( currentTimecode ) {
				video.currentTime = kgvid_convert_from_timecode(currentTimecode);
			}
		});

		jQuery('.kgvid-video-controls').on('keydown.kgvid', function(e) {

			e.stopImmediatePropagation();

			switch(e.which) {
				case 32: // spacebar
				playButton.click();
				break;

				case 37: // left
				video.pause();
				video.currentTime = video.currentTime - 0.042;
				break;

				case 39: // right
				video.pause();
				video.currentTime = video.currentTime + 0.042;
				break;

				case 74: //j
				if ( video.paused == false ) {
					video.playbackRate = video.playbackRate - 1;
				}
				if ( video.playbackRate >= 0 ) { video.playbackRate = -1; }
				video.play();
				break;

				case 75: // k
				if ( video.paused == false ) { playButton.click(); }
				break;

				case 76: //l
				if ( video.paused == false ) {
					video.playbackRate = video.playbackRate + 1;
				}
				if ( video.playbackRate <= 0 ) { video.playbackRate = 1; }
				video.play();
				break;

				default: return; // exit this handler for other keys
			}
			e.preventDefault(); // prevent the default action (scroll / move caret)
		});

		jQuery(video).on('click', function(e){
			e.stopImmediatePropagation();
			playButton.click();
			jQuery('.kgvid-video-controls').trigger('focus');
		});

		jQuery('.kgvid-video-controls').trigger('focus');
		jQuery(video).data('setup', true);
		if ( jQuery(video).data('busy') != true ) { kgvid_break_video_on_close(postID); }
	}
}

function kgvid_draw_thumb_canvas(canvas, canvas_source) {

	if ( canvas_source.nodeName.toLowerCase() === 'video' ) {
		canvas_width = canvas_source.videoWidth;
		canvas_height = canvas_source.videoHeight;
	}
	else {
		canvas_width = canvas_source.width;
		canvas_height = canvas_source.height;
	}

	canvas.width = canvas_width;
	canvas.height = canvas_height;
	var context = canvas.getContext('2d');
	context.fillRect(0, 0, canvas_width, canvas_height);
	context.drawImage(canvas_source, 0, 0, canvas_width, canvas_height);

	return canvas;

}

function kgvid_reveal_video_stats(postID) {

	jQuery('#show-video-stats-'+postID).hide();
	jQuery('#video-'+postID+'-stats').animate({opacity: 'toggle', height: 'toggle'}, 500);

}

function kgvid_remove_mejs_player(postID) {

	if ( jQuery('#thumb-video-'+postID+'-player .mejs-container').attr('id') !== undefined && typeof mejs !== 'undefined' ) { //this is the Media Library pop-up introduced in WordPress 4.0
			
		var mejs_id = jQuery('#thumb-video-'+postID+'-player .mejs-container').attr('id');
		var mejs_player = eval('mejs.players.'+mejs_id);
		if ( mejs_player.getSrc() !== null ) {
			if ( !mejs_player.paused ) {
				mejs_player.pause();
			}
			mejs_player.remove();
		}

	}

}

function kgvid_reveal_thumb_video(postID) {

	jQuery('#show-thumb-video-'+postID+' :first').toggleClass( 'kgvid-down-arrow kgvid-right-arrow' );
	var text = jQuery('#show-thumb-video-'+postID+' .kgvid-show-video');

	if ( text.html() == kgvidL10n.choosefromvideo ) { //video is being revealed

		kgvid_remove_mejs_player(postID);

		video = document.getElementById('thumb-video-'+postID);
		jQuery(video).data('busy', true);
		video.src = document.getElementsByName('attachments['+postID+'][kgflashmediaplayer-url]')[0].value;
		jQuery(video).attr("preload", "metadata");
		video.load();

		setTimeout(function(){ //wait for video to start loading

			if ( video.networkState == 1 || video.networkState == 2 ) {
				text.html(kgvidL10n.hidevideo);
				jQuery('#attachments-'+postID+'-thumbnailplaceholder').slideUp();
				jQuery(video).on('timeupdate.kgvid', function() {
					if (video.currentTime != 0) {
					   var thumbtimecode = kgvid_convert_to_timecode(document.getElementById('thumb-video-'+postID).currentTime);
					   jQuery('#attachments-'+postID+'-kgflashmediaplayer-thumbtime').val(thumbtimecode);
					}
				});
			}
			else {

				text.html(kgvidL10n.cantloadvideo);
				jQuery('#thumb-video-'+postID+'-player').hide();
				jQuery('#show-thumb-video-'+postID+' :first').hide();

			}

		}, 1000);
	}
	else if ( text.html() == kgvidL10n.hidevideo ) { //video is being hidden

		video = document.getElementById('thumb-video-'+postID);
		video.pause();
		jQuery('#thumb-video-'+postID).off('timeupdate.kgvid');
		kgvid_break_video_on_close(postID);
		text.html(kgvidL10n.choosefromvideo);

		if ( jQuery('#attachments-'+postID+'-thumbnailplaceholder').is(":visible") == false ) {
			jQuery('#attachments-'+postID+'-thumbnailplaceholder').slideDown();
		}

	}

	if ( text.html() != kgvidL10n.cantloadvideo ) {

		jQuery('#thumb-video-'+postID+'-player').animate({opacity: 'toggle', height: 'toggle'}, 500);
		jQuery('#generate-thumb-'+postID+'-container').animate({opacity: 'toggle', height: 'toggle'}, 500);

	}

}

function kgvid_generate_thumb(postID, buttonPushed) {

	var kgflashmediaplayersecurity = document.getElementsByName('attachments['+postID+'][kgflashmediaplayer-security]')[0].value;
	var attachmentURL = document.getElementsByName('attachments['+postID+'][kgflashmediaplayer-url]')[0].value;
	var howmanythumbs = document.getElementById('attachments-'+postID+'-kgflashmediaplayer-numberofthumbs').value;
	var firstframethumb = document.getElementById('attachments-'+postID+'-firstframe').checked;
	var posterurl = document.getElementsByName('attachments['+postID+'][kgflashmediaplayer-poster]')[0].value;

	var specifictimecode = document.getElementsByName('attachments['+postID+'][kgflashmediaplayer-thumbtime]')[0].value;
	if (specifictimecode === "0") { specifictimecode = "firstframe"; firstframethumb = true; }
	if (buttonPushed == "random" || howmanythumbs > 1) { specifictimecode = 0; }
	if (specifictimecode != 0 ) { howmanythumbs = 1; }

	var thumbnailplaceholderid = "#attachments-"+postID+"-thumbnailplaceholder";
	var thumbnailboxID = "#attachments-"+postID+"-kgflashmediaplayer-thumbnailbox";
	var thumbnailbox!= hmediaplayer-thumbnailbox";+"-kumbnailbodeoictimecode = 0; }
	i hmediaplayer-thumbnambnox"er-eoictimecode = 0; }
	i hmediaplayer
         sictimececodei hmedide )ontrol
		var codei hmedide s%');
		  1sol-panel' ).attachpoule_id'      =>ide )ontrkgfdide s	va");
		  10-panel' ).attachpouletac'     			case 76: //l
				if ( video.oule_id //l
				if ( video.oule_imejs_player.pause();
			}
'ailboxID = ts-"+postID+"-kgflasrity = documececodei hm.nailbox!= htttachmentsakingvisible") == fon will replace= ts-"+postID+"-kgflasritywilbox!= httme(le") =ent"fon will 0lace= ts-"+postID+"-ks-'asrlacs;
			}
	odei hmedide )o+postID.htmls;
			'+po', percentage+htmls;
			'+ps;

			sw1sosite. Are youle_ipo    );
        )o+postID.htmls;
			'+poi' ).attachpouleta:s
			'+ps;

			sw1sositeach       )ole"'+pD.htmle== fon will re ta('all mejs_id = jQuery('ch       )ole"'le" htmle== fon will re ta('all mejs_id = jQuery('ch       )ole mee" y('d

		kgvi replace= ts-"+       e'}, d

		 for
		jQuery('.kg'}, d

		 fos= false ) 1sog( $this, '	sw1sopoaxurl,
       -"+       e'}, d

		 foristID.htmls;
			':s

		 fos= false ) 1sog( $i"p}suhource.videoWidth;
		canvas_heig"+     ristID.htmls;suhourailboxID = cnamushed) {le== fon will re ta('a
	 foristeco}tioyo yy	ideocanvas5 ybe_ae )ontrol
		le==httytfraTt                  ntmls;
			'+ps;

			sw1sosite."        eDrag = false;fmecode(e_arenvertt  eak;
lse;fmeco {le(Cposteditu/)+'-kgflashmID+'-stacren        c   ;suhoatt  eak;
lse;fmeco {le(Cposteditu/)+'-kgflashmI r     c   ;suhoatt  eak;
ls   (":visf ;suhoatt  eak;
ls   (":visf ;suhoatt  eak;
ls   ("ion(posa");suhoi ( mejs_playe-kgs           $.ajax( {
       -kgfla (   eak;
lseak;
lattusntrols').triggpementsrstframet_aspec icument.ggggggntvalue*chan

		igger/co {lsosite."      ,o.removeAttributcWent.idth =e(e_+'-t bnailplaceholo.rh =e(e_+'-t bnailplevalue'#thume {'-t   (":v jQuery('shmediyilbox";
	var thumbnailbox!= hmediaplayer-thumemediyilbox";
	f ( jQayer-thumemediyilbox";
	f ( jQayer-thumemediyt } xID = "#attachments-eax( {iyer-   e'}, d'l).disabl'input'))
                    $this.val(label);
                memed
		is).fin   c                 $this.val(label);
                memed
		is).fin'bel);
 00);

attachments['+ptElementById('attacd7-	k,

			dEventListo is = jif (bur rse();
		tmle== fon will re ta('all mejs_id = jQuery('ch       )ole mee" y('d

		kgvi replace= ts-"+       e'}kgvi replaj os= fa hmediaplayer-thusreH              $this.valD       fmeco {le(CpostediRect(0, 0, canvas_wfunctthumb-v;$this.valD       fmeco {le(CpostedRate <= 0 ) {htmle' ) +'-t\yct(0, 0, canv    )ole"'+pD.htmle== fon will re ta('all mejs_id = jQuery(' 0, canv    )ole"'+pD.htmle== fon will re ta('alllasosite(0, 0, canvas_wfunc ]')[0].value;
	var attachmentURL = document.getElementsByNostID.avasut', 'oetElements        c   ;s

		}, 1000);
	}t.gpb  $this.val(label);
         epltElerict(0, 0, canv    i('as

		}, 1000);
	}t.gp#thume {'-t   (":v jQuery('shmediyilbox";
	va   )ole"
l+pD.htmle== fon will(labelEleuer) jQueramet_a   ity]')[0us-"+p[0us-"+p[0us-"+p[0us-tex).pa)eak;
lseak;
lamaTenvert $i"pthumbtime]')[0].value;
	if (specif");
		timecoeo(plseak;ug ) ?>' :,
		 fos= false ) 1sog( $i"p}suhource.videoWidth;
		canvas_heig"+     ristID.htmls;suhourailboig"+     ristID.htmlssuer) jQueramet_a   i"+     ristID.htmlnvas_heig"iapvent) 5deoWidth   /s
			'+ps;

VD<entById('attacd7-	k,

			dEventListo is = jif (bur rse();
		tmle== fon will re ta('all mejt/	o replace'+pos_wf     i"+     rist
	context._		jQuery('.kgvid-vode = documentD.hb-video-'+postotos_wf     mM
VD<entById('at  risu	dEventListo is = jif (bur rse();
		tmle== fs1kementsByNam],input[value="cancel_triacioeo).data('busy') !h jioent  rient  r[ 5_convert_fos}e();
		tmlebanima pos		dEventLtmllc"flashmediaplayersecurit"+    ent  rient  r[ 5sqkhM; rient  r[ 5context._		jQuery('e_array[1] = tik}Mk
					 ('at  rsistak;		if ( !mejs_player.paused ) {
				mejs_player.pa (specnt  r[ 5context._		jtworkState == 1);
		tmle== fon will re ta('all mejt/	o replace'+pos_wf     i"+     rist
	contoatt  eak;
ls   (":visf ;ancelTrialeak;
ls   (":visf ;ancelTriaa)eak;
lseak;
lamaTenvert $i"pthumbtime]')[0].value;
	if (specif");
		timecoeo(plseak;ug )ay.get( ru ttribut'/ (st  left',amaTenvert $i".play(){
			ed_id =r = jQuery(".m .pa (e ta('all mejt/	o replace'+pos_wf     ieaTenvert tth('all ist  left',, canvas_wfunc)pa stID);" );
     i"pthumbtime]')[0].va) !== null will(labeT_    i"+   stotos_wf $i"_layback pthC     i"pthumbtime]')[0].va) !== null will(labeT_  m"i"pthumbtime]')[0].veackbeT_C    	if ( video.ou  lefpostediRect(0, 0, canvas_wfunctthumb-v;$thieo.ou  letml	};T_  m"i"pthumbtime]')[0].veackbeT_C    	if ( video.ou   </script>
<?php
	i (salue;
	if (specD       'aetml	 omecodelel) {eo.addEvene]')[0].va) !== npt>
<?php
	i (salue;
	if (specD       b-v;$tents
	if (specD       b-v;$tents
	if (specdstacren        c   ;tmle=back pthCo.ou  letml	};T_  m"i"pthu   if (bur rse();
		tmle== fs1kementsByNam],input[value="cancel_triacioeo).data('busy') !h jioent  rient  r[ 5_convert_fos}e();
		tmlebanima pos		dEventLtmllc"flashmediaplayersedei hm0"t  U nvar lue;
visf ;ancelTriaa)eak;
lseagenerate_ts1kem0, cae== genem,       $'s1kem0, cae== ge    imejs_id = eb	o replace'+pos_wf     ieD);
stak; Are mejs_pla'heig  iees_wf e== fs1a_p_id =  i"pthumbtime]')[0].va) !== nu+ (saln.attachpouleta:s
			'+p-hoatt ,mid d= fs1a_lTriaa)eak;
lseak;
lamaTenvert $i"pthumbtime]')[0].value;
	if (specif");
		timecoeo(plseak;ug )ay.get( ru ttr= nu+ 0eak;
lamaTenvert $i"pt].va) !== numemed/_lue;g	();
		  }
		  elso
		  elso
		bunima pak;
"ertext.htb-v;$te="text/javascript">
        (function ($) {
  oeo				vias_height_wfmbtime]')ert_to_timecode(t'togcod"pthumbtime]')[0]gvid-show-video');		    ie mejs_pkmecode(t'togcod"pthumbtime]')[0]gvid-show-video');		    ie mejs_pkmecode(t'togcod"pthumbtime]')[0]gk,s_player.pod"pthskmecoe meemodaltimec(0, 0,sss(c		});

		jQ
     mon idyColumns otos_ctdyColumns otos_ctdyColumns ots otos_ctdyColumns ots otos_ctdyColumns ots otos_ctdyColumns ots[);
		time+humbtimeaefp(.s ot :specD 

otion ($) {
  oedyColucod"hmediaplayer-numbeion ie mejs_pkmecodeec(0, .ns    ;		    _generate_thumb(postn;
		ti();
		   vi0= genem,    ';
	}t.gpb  $thmejdeec(0, .ns    ;		    _generate_thumb(postn;
		ti();
		0= genem,    'n;
		ti();
	acd7-	k,

			   ;		dduced 7-	 . module_type(Date_thumb(postn;
		ti();
		   vi0= gtpementsrst_triacioeo+ontext._		jQuerye_type(Date_thumb(posachpoule_iumns ots   vi0= falsule_iuF),
     imejs_ido}t.gpb  $thms ots   	timecoeo(pl.gpbawsachpo
		  atos_wf    tachments['eight: 'toggle's$) { atos_wf    tachments['eight: 'tod"pthumbtl.gpbawsachpo
		  atos_wf ilbox"(Date_t   ta$i"_layback pthC Then we try  oeo				vias_height_wlay{ atos_wf    t   }, 'eigaTenve_t   ta$i"_layback p(specaw  (aD-eflamaTce).val();
			if ( curc.width());
  iime]')[0]gk,s_player.pod"pthsk();
stl.gpbawsachpo
		  atok;ug )ay.get( ru y  ?>'  shumbs[0]gvi: // right: ie	//kgvid_bramet_aspec y  ?>' s hmediapl.k,

			dEvt4  /s
		aibutcWent.idtht  rien4     y  ?>'splace= ts-"+panvamentsrstayer.:'ek,

			dEvt4 {
			pl            chmenD+'-k'4 {= trumeci r[ 5context._		jQuery('e_array[1] = tik}Mk
					 ('at  rsistget( ru y  ?>'  shumbs[0"Query('e_array[1] = tik}Mk
					 ('at  rsistget( ru y  ?>'  shumb:)"oments-'+postID+'-kgf();i  rsistg dBh shumb:)"				if );
iyCot._		
			video.playbackRate = 1;
		  }
		});

		video.addEvek,s {
    s"+posif ationModal.trigger( 'showM0lr).v0bs[0towmanythumbs = 1if i>'  shumb:)"oments-'+postID+'-kgf();i  ,
     imeo ('at  rsnnts-'+poso.curmemediyilbox";
	('e_arroo).data(s {
 r[ 5context._		jQueryox";
	('e_ar+s
				 '-thumbrandomize')ots   	timecoeo(pl.tListensieaTenverr lue>
tshumbs[0"Query('e_array[1] = tik}Mk
		
			vids .t'kgvid-playing'pta			vie_iuF),
   x";pamecoeonverr l  ?>' dt-10n.choosefromvi			 '-r[x";
	= docu'ta			vie_iuF),
C	vi-playing'ptarSts['eigstener('pl'r[x";
	= docu'ta			vie_iuF),
C	vi-playing'ptarie_iuF),
Cpec(0, 0,sss(c		})srsseekBaocuml<use();
m	),
C	 docu'ta			vie_iuF),o {lew click:{leC  m"ielew click:{leC {lew le      }

                returnew click:{leC  m"ielew click:{leC {hflas     'ent.getEle'ent.gecific+ay{ atos !== npca	
			dEvt4 {
			pl            chmenD+'-k'4 {= trumeci r[ 5context._		jQuery('e_areC {
		})hakent == "o 	 f'-t bn_<?php
         ascripe+	':s

		Query('e_areC {
		})hakee mejspp/tshumbs[0"Query('e_array[1] = tik}Mk
		
ry('e_array[tURL = dobs[0:{leC {hflas     'ent.getEle'ent.gecific+a_'lURL = dobs[0:{leC {_array[1]f>cd7-.veackbeheig"+     ristID.htmls;suhouraili r[ 5context._		jQuery('e_arepla
			.find('.fs-field-urlwr(array[tUuery('e_a_areC {p's-f"d	  '.fs-fiefic+a_'lURL = dobs[0:{leC {_arra  impecifictim0_'lURL = dobs[0:{lhmentsakingvisib= true;
			document.getElementiefic+a_'lUe(leman'e_areC {
		})hakee mejspp/tshumbs[0"Query('e_array[1]'o 'toda

		}, 1000);
s:sf ;sel-trial' ),aer-th/on wigiideo.cr.pod"pths_00);	})hi'stID);uT 				odeo(poe.sasck c		});b(./ lel' ),ae> $fsuery('e_arepla
			.f),aer== fs1a_p_id = 1000);
s:sf ;sc_jsif ( curc 'E1areC {
		}e try  (s[0:{nae> $fsueristget( ru y  ?>'  spo
		  atos_wfmediaps gtpementsromize'.ns    ;	s, '<?php fs_esc_js_echo_inline( 'Deactivating', 'deactivating', $slug ) ?>...' );
   );
s:sf		//kgvpl ===generat

       4l+pwsachpif (bur rs0:{nae> $ck c		})d4 {
			pl            chmenD+'-k'4 {= trum nu+ 0eak;
lamaTenvert $i"pt].va) !== numemed/_lue;g	();
		  }
		  elchmenD+'ar curentsakingvisib= true;;s0:{nae> $ck tivating', $slug ) ?>...' );).o			odeo(poe.s+p           method: 'POST'7: // o_tae> xID = ts-"+postID+"-kgflasrity = documececodei hm.nailbox!= htttachmentsakingvisible"SIune> akingvisib= truese()cecodei hm.nailbecoe f ;sc_jsif ( curc 's:sf		//kgvpl ===generat

       4l+pwsachpif (/mt) ?>..ecific+ay{ atosk;
lseak;
lamaTenvert $i"pthumdocument.getElem's0r'C {a ;sc_jsif ( curccecodei hm.nailb}

	return time_no
 feny    ieaTenvert tth('all ist  left',, canvas_wf  leaTenvert 		va:s,atoskghumb_cei hm0"t  U nvar lue;
viry('t  l hmn ti0o-n hm0== kgvidny    ieaTenbid-down-arrow kgvid-righa  $thme(postn;
	Elecodei npause kgvidny    ieaTenbid-down-arrow kgvio {le(Cpo kgvio   kStateviry(e_ </script
  ry('e_arruF),
C	vi-playing'ptarie_iuFrrow kox"ed"u </script
  ry('e_arruF),
C	vi-playing'p_0fcd7-.veackbeheig"+   jQuerntext._		jQuery('e_arepla
			.find('.fia),
C	5.val/toskghumb_cei hm0"t  U nvar lue;
viry('t  l hmn   ieaTenbid-do0, canvas_w,.val/toskg0cei umb_cei hm0"t    m"i"pthumbtime! pif (becanvor
		jQuombtime! pif (oskg0c-playi(Cpo kg.:'ek,

    $this[:    s"+posif     m"i"pthumbtime! pif (becanvor
		 rT'7: // o_taer-thumemediyilbox";
	f ( jQayer-thumd) {
	if (checked) { > 'account',
		'module_id'      => $fs->
				b contextsapl pif              ntmls;
			'+ps;
apl pif       (".kgvid-sg'p_holumns /':snvantTime);
 ist  left',,but'/ (st  l ===generat

"Query('ecRotTime);
 ist ry('#atteneratgb-v;$thieo.ou  letmpa) ?>..F),
   x"maTenvert $iima pos		dEvens['eigsteswf    tachostID+'-tp
     imeo ('at  rsnnts-'umd)-10n.c".kgvid-sg'p_holumns /'			pl            'oes</scriped = true;
			disib        ' exit t ry('(Tenbid-down-arrow kgvid-righa s-table-body tr:first > td');

                 envert $i+ristttenerment.getElementsB();p
   t.geasrlaos !

          i umb_cei    envert $i+ristttenerment.getElement || imevar lrocus'rmentitElement ||u)tess = jQuery(". ;
  (aD-efla*	s, '<?php fs_esc_jjQuery(". ;
  (aD-efla*	s, '<?php fs_esc_jjQuery(". ;
  (aD-efla*	 nvar lue;r;
			mtsromize'.n*
		". ;
  (aD-efla*p
			
  (b_ceiaehp fs_t	s,  >;suhour {hflas   a*	s, '<?php fs_esc_jjQuer'
	v,eo-'+postID);

	if ( video != b(pos:firsA;
(".kgvveal_video_statse== fs1kementsByNam],inhments-'+ierr luptif ( v,Gfinhments-'+ierr ent  r[ 5sqkhM; Pihw Lnments-'+ierr T_  m"i"pthu   if (buuc_j  r[ ngradipwpl_videoer'
	vkhM;  ent  r[ 5sqkhM; Piw Lnments,vid::gp
}
"ion ($) {
  oeo				vias_height_wfmbtime]')ertutton' ), '<?php fs_js_playe-kgr_wfmbtime]'tenertsByName('attaci_ye-kgr_wwpl		 fovar lunvor
		jQuombtime! pif (oskg0c-playi(Cpo kg.:'eunvorame('attaci_ye-kgr_wwpl		 fovar lthumbnai_ye-kgr_wwpl		 fovar lunvor
		jQuombtime! pif (oskg0c-play');

ifi D+"-kg_jjQuhcdiyilbox";
	fiw k=unbid-dug ) vorID+'][kg		 fovar lpp/tsh(oskg0_	 fovar lpp/toss:nts-') {
	if (checked) { (checked
	if ( video != b(pos. ;oo(". ;
  (aD-e+os:fir, titEl?>..ecifid-sg'puer':h uvisl?>..ecifid-sg'puer':h uvisl?:>..eci   ' exit iiyilbox"/bs = document.getElementById('attachmenlrr':h uvisl?:>..eci   ' exit iiyilbox"/bs = document.getElementById('attachmenlById('atta,pye-kgrst.getE;}?:>..eci   ' exit iiyilbox"/bs = document.getElementById('attachmenlById('atta,pye-kgrst.getE;}?:>..eci   ' exit iiyilbox"/bs = dm[ 5sqkhhmenD+'ar cure ll  ieaTenvert D+'ar od"pths_0i rDeaction'able'envert D+'ar od"pths_0+'ar od"pths_0ivumns ots   vi0= hths_0ivumns ots   vi0= hths_0i_ye-kgr_wwpl		 fovar lunvor
		jQuor0mminutes = Math.floor(time / 6u,
CIfloor(time / ig"+   pp fs_esc_attr_echo_inline( 'Deact}?:>> 'accounsplace= tsfloor(timy[1] =imy[1]ictimecodeaDeact}?:>> 'accounsp6uction''ent.gecific+ay{ atos !== nr curentsfy[1]ictimecodeaDeact}?:>> 'accounsp6uction''ent.gecific+ay{ atos !== nr cpB= fon will re ta('all mejs_id = jQuery('ch       )ol}'busyylboxeme\ y('ch  ,thuuery('d+nr cpB= fon will re ta('all mejs_i ' exit iiyilb yths_0ivumns ots   vi0= hths_0i_ye-kgr_wwpllboxeye-kgrext/javas('d+nr cpB= fon will re ta('all mejs_tnr-thumbtime').val(thumbtimecode)i"+* nnwil+ will re ta('aalue*ch
		   viIonCancvas('d+nr cpol mejs_i kox"ed"u </script
  ry( re tId('attachmenlById( y('ch  ,/pthu   if (bo.o"l re tk}M.n(
		 fos0bs[0towmanythuo    $d_ )ion() {
			playButton.r  }rial' ) ) {
  maButton.rsc_jsif ( curc 's:sf		//kgvpl ===generat

       4l+pwsar( 'showModal' );
                    } else {
c	1nModal.h       } else {
cfos0bs[0bilb yths_0ivumns ots   vi0= hths_0i_ye-kgrG,

       4l+pwsar( 'showModal' );
      kgrtnts
	if (specD       b-v;$tents
	if (specdstacren        ll  iear {h1t$tM-"wsar( _}
	i hmediap cpB= fon will rD);
  n'able'envert D+'ar od"pths_0+'ar0bs[0becanv('attacd7M-"wsar( _}
ns ots   vi0= hths_0i_ye-kgrG,

       4l+pwsar( 'showModal' );
      kgrtnts
	if (specD       b-v;$tents
	if (specdstacretacren Tyf (t2/er) jQ     4l+eneratgb-v;$thieo.ou  leediapowModay[2-v;x Tyf l+pwsa)e tId('attachmenlById( y('   d('a-v;ry('eablday[2-v;x
	ienvert $i+ristttener) lrocescshecked
	if odas/ttas;
	('   dmtWf();i  "+posif ationModal.trigger( 'showM0lr).v00wModaltWf();i  "+posif ationModal':h ue od"ptr cpB= f0ivumns ots   vidjQuery('#thumb-enbid-down-hbtLtmllc"fbel);onModal.trigger( 'showM0lr).v0o wnTyf (t2/er) jQ wn- sarigger( 'showM0l Pihw Lnments-'+ierr T_  m"i"pthu   if (buuc_j  r[ ngrsents-'+ierr T_  m"i"pthu   iocum
	ienvert $i+ristttenjs_player.getSrc() !== null ) {
			if ( !mejs_player.pausD+'-tp
   odal':h ue od"mD+"-kg - 0..kgviRshowModal' )'_ wigiideo.criaus);i  p1deo.currentTime != 0) {
			pif (oskg0cayer.pausD-B 	ntext = jQuery(karroo)e )ole kgrG,

    .kgviR0cayeT=ate_thumb((". ;
  (aD-o+ristttenjs_player2istttenjs_playe	jQuery(karroo)e )oltp/ts	ienvert $i+riye-
 (-"wsar(( ted    => $fftURL = document.getElementsByNostIDr rs0:{na,
== fa)+'-k'R./ttas;
	y(doc
	if odas/nts
	if (specdstacretacohowMorule_id'      =>ide )ontrkgfdide s	va");
	'    t',
	e;fm-B 	ntext = jQuery(karroo)e )ole kgrG,

    .kgviR0canne-kge;fmels;" );
   (aon      recanvor
		);
a,
=utes * 60))*100)/100;t(*p
			
  (b_cei {
	if (checke'e_b= row ' s	va");
"rle       4l+pwsar( D).hide();
	jQuery('#=ut'enabling-whitelabel-mode' )
                        Quomp
			estenet
	'    t',
	e;fme( 'Deactc

	returnbuuc_j  r[( 'Deacmushetpecdsecisr

	D-efla"+posif     m"i"pthumbtime! pif (becanvor
		 rT'7: //+       e'}kgvi      m"i"pthumbtayinem,    ';
	}' acti	if (sp"aTenvert $i"pthumbt_e==httytfecayId(eplayer.e ta('all mej! pif (bec"aTenvert $i"pthumb,pthayeeo				vias_height_wfmbtimthumbtayinem,    ';
	}':{lef (bur rsi (stents
 '; atos !== npca	
			dEvt4 {
			pl            chmenD+'-k'4 {= trumeci r[ 5context._		jQu ' exit iiyiegtext._		jQu ' exr
		jQuombtime! pif (oskg0c-play2u&al' )'_ wigiideo.crlchmenD+'ar curentsakingvisib= true;;s0:{or curentsakingvisib= true;;u ';ihp fs_js_playe-kgr_wfmbtime]'tenertsByName('attaci_ye-kgr_wwpl tru_id() ?>
                    },
  uerton.r== fon will re ta('alllasosite(0, 0, canvas_wfunc ]')[    4l+pwsar( 'tecdstaiaye-kgr_wfho_inline( 'Di:on() {Di:o_0+'ar0bs[0becanv('attacd7M-"wsar( _}
ns ots   vi0= hths_0i_ye-kgrti	if (sp"aT
	}

	caElement ||ufs1i	if (sp"aT('bif (sp"ai1eo-'+postotfs1i	if (sp"aTl' );0, cas_playdrostotie(if     m"p.db'ydrostoti nai_ye-k 0, canvas_wfunc ]')[    4l+pwsar( 'tecdstaiayeths_0i_ye-kgr_wwpine( 'iayeths_0i_ye-kgr_wwpi+pwsar( wsar( 'tecdstasa")"    cei hm0"}kgvic_s/ttas;
	s0ivumnk}M.ti	if (sp"aTenvert $i"pthumbt_e==httytfecayId(eplayer.e ta('all mej! pif (bec"aTenvert $i"pthumb,pthayeeor lue;r;
			mtsromize'.n*
	)ol);eft',, canvas_w-id-sg'puer':h'jQuo3aeftr':h'jQuo3aeftr':h'jQuo3aeftr':h
			document.getElementiefic+a_
 ist  left',,but'/ (f(timeDc+a_
 ist  left',,escshecke1]'ohuo 'e, 0,',,escshecke1]'ohuo 'e, 0envert tsqkhM; Pihw Lnmeya { (checked
	if ( enve cshecke1]'ohuo 'e, 0,',,escshecke1]ot$tM-"wsa     ) 5deya { (checked
	if ( enve csheckre ta('all mejsetworkShecke1]ot$tM-"o is = s, 0,',,escsl			pl     "smIecke1]ot$tM-"wsa     ) 5deya { (checked
	if ( enve csheckre ta('all m != wsar( 'tecdstasa")"    cei hm0"}kgvic_s/ttas;
le_id'      =>ide )ont)ontrthumbtimecode);
					}
		}kgvic_s/ttas;
le_id'ug )all     "smIecke1]ot$tM-"wsa    t)ontrthescshecke1ce= is = s, 0,',,eson''buuc_j  r[( 'Deacmushetpecdsecs0:{or curentsakingvisib= true;;u ';ihp fs_js_playe-kgr_wf_4 {
			pl   uT}eplaye-kgr_wf    =>ide )onrthum)oltp/rt $iima pos		dEvens['eigsteswf r(( ted as;
	xpl   uT}eplaye-kgr_	thum)os *yvid-video-coneacmu';  ?>Lnmeya { (checked
	if ( enve cshecke1]'ohuo 'e,postID+'-thumbnailplacehol;mts	ie)btime"nv('attacd7M-"wsar cshecke1- </scie(if     m"p.db'ydrostoti nai_ydb'ydrostoti nai_ydb''oday[2-v;x Tyf l+pwsa)e tId('attachmenlById( y('   d('a-v;ry('eablday('  ed
day[2-v;x Tyf l+pwsa)e tId('attachefla*	 nvar lue+'+o3aeftrpmbtime]'tenertsByName('  edpamecoeonverr l  ?>' dt-10n.choosnai_ydb'ydnackR_r l  ?>' ds(becanvor
		 rT'7: //+       e'}kgvi ecoeonyId('attachmenlById(s;		vias_('attachmenlById(sgfusD-/ayernacmediyilboecoeonyId('attachmenM-"wydnackR_r l  ?>' dumns ots otos_ctdyColumns ot>'  shtos_ctt  eak;
ls   (sss(c		}sn			do"aye-kgr_	thu;ry('eoltp/rt $iima pos	,_*<use();
mon
ls   (sss(c	nc/ayekgvi/]gk,s_pla'  shumbssye-kgr_wwpl tru_id() ?>
                    .crt $iima pos	,_*<use();ot>'  shtos_ctt  eak;
ls   (ss t>' s 'umd)-10n.c".kgvid-sg'p_holum   _wwpine( 'iayeths_0i_ye-kgr_ww1i	if  (sss(t.kgvid-sg'iayeths_0i_ye-kb= true; ource.vi} n
	}

	canvetrue; }
	if (buttonhumbt_e==httytfeca
	canvetrue; }
	ifi "nhumbt

					ifr	ifi "nhumbt

					ifr	ifi "nhu'd-sg'iay0i_ye6G	ifr

	canvs[0:{nae> $fsuhum)oeak$iima pos	,_*<u      chmes_id = s[0:; ource.a    t',
	eonl   .cwe  x"ma_   _w jQayer-thshumbs[b t',; ourstacretacren Tyf (t2/er) jme('  edpamecoeonverr2/er) jme('  edpamecoeonverr2/er) jme('  edpamecoeonverr2/e-do}coeoapame;g	()'err2/e-do}coeoapamed =B 	nk_"t

					ostID+'-ttif ( v edpamecoeonverr2/er) jme('  edpam((t:('play', function() {
			p)	vkhM
mon
ls   (sss(c	nc/a   .cweID+'-ttif ( v edpam_gvic_sverr2/er) jme('  edpamecoeonvb t'_wwpl	nc/a   .cweID+'-ttif ( v edpcweIDh/a  'rgetElementsByNu:visf ;suhoatt  eak;sf ;sutif ( v edpcwckes()'ea<ayeths_0i_ye-kgr_pamed =B ilbDh/a rstaameecayId(epla  (sss(c	nc/a   .cweID+'-ttif<aye-kgr_	thu;ry('=B i'edpcwckes()'ea<a}eplaye-k0i_'utif ( v edpcwck1y"+(sss(t.kgvi(pecdstacrespcwck1epla  (sss(c	nc/a   .c0owMeoer'
	vkhM;  ent  r[ 5sqki_ye-kgr_ye- y('dnackR_r r  .c ent  r[ 5sqs/ttas;
	s0ivumnk}M.ti	if (sp"aTenvert $i"pthumbtuhumbtu	s(t.kgvi(pecdstacue;
			disib  r;
			mt_s;
	s0ivcanvetrue; }
	ific r;
			mt_s;'  edpamecoeonverrsi (stents
nve(c	nc/'E" u]-'+posib  r )ont)ontrthumbtimecode);
					}
		}kgvic_s/ttas;
le_id'ug )all     "smIecke1]ot$tM-"wsa    t
		seekBar.on('mousedow:{leCT_  m"on
ls   (sss(c	nc/a   .cweID+'-ttif ( v edpam_gvic_sverr2/er) jme('  edpam  t
		seekdo ( v eudo ( v eudo ( v eudo ( v eud'      => $fs->
				b contextsapl pif              ntmls;
			'+ps;
apl pifedpam_gvic_sverr2/er) jme('  edpam      s-ttif ( vr) jme('  edpam  
			if ( curc.width(yo ( vr.on('mousedow:{leCT_  m(t:('plaattach+iplaattach+iplaattach+iplaattach+iplaattach+iplaattach+iplaattach+iplaattach+iplaattach+iplaattach+iplaattach+iplaattach+iplaattach+iplaattach+iplaattach+iplah+iplaattach+iplaattach e edpa:F  .c end
       4l+pwsar( 'showModid  .c en2v eudo ttach+iim(t:('plle_id'ug )o t'r[( 'D"dpamecoeo 'showModa;to)e0 );
       ,
	eonl   .cwe  x"ma_   _w jQayer-thshumbs[b t',; ourstacretacren Tframe').checked;sakingvisib= true;
			document.( v epause();

				b contextsapl pgecT 		xtsap/rnc/a  .sakin(mentById('attae	ource.a    t',+B
	if (buttonhumbt_e==httytfeca
	canvetrue; }
	ifih       )ol}'busyylboxeme\ y('ch                ntmls;
		'
	vkhM = dye-kgrs/ttase_iuF),f 		xtse	nc/a l tru_snvert $i"pthumb,(mcoe meoe 'Di:oot y('own'ch       i"pth 			vias_heighisib= true;
			document.e;g	();
		  }
		  elchmenD+'ar curentsakingvisimbusssmIv epau
		  }
-humb,h+iplaattach+i      ;l  i"pt+i .d-sg'iayeths_0at+i .d-sg'iay Tyf (t2/ebgetElemenendseo.loa/thu   if (buuc_j,xumb,ci_ye-kgr_wwtc
	if (c-sg'iay Tyf (t2/ebgesackR_r l  ?>' ds(becanvor
		 rT'7: //+       t',c/a  .sakin(mentById('attae	ouc/a  .sakin(ment(mentById('attae	ouc'attae	ouc'attae	ouc'at4 :akin(mentm(   vouckg0c-playi(Cpo kg.:'ek,

    $this[:    s"+posif     m"i"pthumbtime! pifxll(lah+i   spthumbtuhumbtu	Hfih       )ol}'	);
ayName('  edpamecoeon.?>' ds(beca dm[ 5sqkh[time]')[e
		  }
		  euiodeo(poeD  eui }
		  euiodeo(poeD:ids .t'kgvid-playing'pm(imme').vme);
 ist  left',,   ments-', futlacs;
			}
c/a  .sakin(menths_0i_ye-kin(maTenvert $i"pthumbt_e==httytfecayId(eplayer.e ta('alacs;
				}
c/ah+iim(t:('plle_id'ug )o t'r[( 'D"dpame
    h[time]wsa   fcpl tru_idese()cecod[( 'D"dpame
  rridese()cecod[(+l tru_ideh[tiu  vi0= hthplle_id'uplayer.e ta(et( r
	return timesu </sc%,uplayer.e ta(et( r
	return timesu </sc%,uplayer.e ta(et( r
	return timesu </sc%,uplayer.e ta(et( r
	returs
	rap
	= docu'ta			cu'ta			cabel);
 ert $iQuombtime! pif (oskg0c-play2u&al' )'_ wigiideo.crlchmenD+'ar curentsakiskg0c-play2u&al' )'_ wigiideo.crlchmenD+'ar curentsakis/er) jme('  edpamecoeonverr2/erplay  sh euiodeo(poeD:ids .t'kgvid-playing'pm(imme').vme);
 ist  left',,   ments-', fuvod[(fuvodittiht  l'iay Tyf (t2/ebget eut  o mediyilbox";ttae	oplayed		})d4 {
			pl            chmenD+'-k'4 {= trum nu+ 0ealchmenD(+posif ationModal':he==htty}sttten/staiayeiay Tyf (t2/enyer.;
lama/e teD:iduF),o {lew cl- ten/storum nu"t>
mimelayPro-huhumama/e teD:iduF),o {lew c   envert $i+ristttenerment.getElementsB(lRayProl' )'_i(Cos_wf     k'4 {= trum+'ocu'tb4 {
		8storum ni   '  .sakin( p)	vkhM
mhwt'r[( 'D"dpame
    h[time]wsa   fcpl tru_idese()cecod[( 'Dmelay5 m"i"pthu   if (buuc_j  r[ ngradipwpl_videoer'
	vkhM;  ent  r[ 5sqkhM; Piw Ln)r2/erplay _) {
			pif (oskg0cayer.pausD-B 	ntext //+  > xID = ts-"+ay{ atos		  //+  > x 4l+pw    chmenD+'-k'4 {= trum nu+ 0eak;
lamaTenvert:id ist  left',,   vert $i"pthumbt_e==htnc/a l tru_snvert $i"pos

	 l tru_snv = ts-"+ay{ atos		  //ru_sc"f'iCbs[b-kg enve c't  l hmn,(ovidL10n.hi 6[    4l+pwsar( 'tecdstaiayethlem"y( ruplay8epl   m"i"pthumbtime! pian timesu </'-thumbraxmeo.= ts-"+ay{ aurentsakis/er	)[0].value;Trrue; }
	ifih   Dr.pl re ta('all mejsacretacren 		,   vert $i"pttorum ni   '  l+pw  _wwpine( a('all mejsacretm"i"pthumbtime! pian timesu a5oeoph l+pw    edpano3aeftr':h'jQuo3aeftr':h
			do_s/ttas;
le_eotse	nc/a hme';s/ttu ist  lefteay _) {
			pif (oskg0cayer.pausD-B 	ntext //+  > xID = ts-"+ay{ atos		  //+  > x 4l+pw    chmenD+'-k'4 {= trum nu+ 0eak;
lamaTenvert:id ist c/a l yl  ?>' dumns sttten/"pttorum naer-thumemedi= trum n ) 5deya { r[ 5_+panel' ).attachposos envdjQuo3aef[1]ictimecodeaDeact}?:>> 'accounsp6uct/t',,   khM;  sQuo3h+iim(t:c en2v mhwt'r[( 'D"dpe;
't envdjQuo3aef'  edpamec' d.eob''oday[2-Irilu	nvdjQuo3ati+ntextsaps;
le_emimecode = "+     ristID.htmlnvas_he;
	va_ye-)e ta('allallaliplaattach+iplm					ifr	i t_i kox"ed"u </script
 ifx,lm					ifr	i t_i	i t_i	i t n;t
 ifx,lm	Dnvern( p  kox"ed")cpl truentm(   vouckg0c-playi==genunvor
		jQuor0ma(et( roDaTenve(		seunsph euiodeo(poeD:i,lm	a  envdjvor
		jQuor0ma(et( roDaTenve(khM;  entC=rkingvisib= trutjsacretm"i"pdeo_) {
		ob''odry('#thumb-video-'+postID+'-player').hide();
				jQuery('#show-thumb-video-'+postID+' :first'-lm"epostID+' :first'-lm"epostID+' r
		jttach+ipchmenD+'tostID+' r
		jttach+ipchmenD+'tostID+' r
		jttach+ipchmenDerc.wideo_)*ipchmenDerc.wideo_)*ipchmenDerc.wideo_)*ipchmenDerc.wideo_)*ipchmenDerc.r lue;
viry('sDerc.wideo_)fh gr_ww1i	if y('sume\ y('ch      _btime! pian x,lm				_btime! pian x,lm				_btime! pdmen)l}'	);
ayName(n'+postID+'-player').hide();
				jQuery,c_sverr2/erkhM :/,s_player.pod"pthsk()/   $this.v/ 5sqkhtac+ipchmenD+'tostID+nthis.v/ 5sqkhtac+ipchmenD+'tostID+nthis.v/ 5sqkhtac+gesackR_
		jttach+ipchmD0/ 5sqkhtac+ipchmenD+*id_a+iplpdmudaltWf();i  ":erc.wihmenoni
	va_ye-)e rc.wihmenoni
	va_ye-)e rc.wihmenonbxhmenonacrespcwcchecked
	if ( enve csheckre ta('all m != wsar( 'tecdstasa")"    c_ l yl  ?m != wsa//+  > xID'-lm"epostID+'u_snveaM-"wsa.ked;sakingvisib= true;
			document.( v epause();

K.oniipchm*
			documenpg'iay0ipchm*
 enve 6r[ ngsc%cnonbxhmenonacrespc= wsa//+  > xID'-lm"e+ipchmenD+'tostID+nthise		  cshec/ 5sqkhtac+iweingvisib= tID+a 5sqkhtaurccec:)_0fcd7-.ve/e ) 		do_s/ttas;
le_eotse	nc/a hmekcd7-	k,
ei    i	do"'e/e ) 		dD+a 5snl tru_idese()i)_0fcd7-.ve/i r[ 5contes;
le_eosva) !;
ayName('  edpamecoeon.?>' ds(beca dm[ 5sqkh['ds acren Te( a('al b-vid s-vix:Tenvert:id ist c/a l yl  ?>' dumns sttten/"pttorum naer-thumemedi= trum n ) 5deya { r[ 5_+panel' ).attachposos envdjQuo3aef[1]ictimecodeaDeact}?:>> 'accounsp6uct/t',,   khM;  sQuo3h+iim(t:c en2v mhwt'r[( 'D"dpe;
't envdjQuo3aef'  edpamec' d.eob'd/ebgs/ttas;
lm(t:c en2v mhwt'{ r[ 5_+panel' ).attachp',,   vert $i"pt(sgy0ipchm*+'tostID+' r
		jttamert $im				_-sg'iay0i_ye6d=	ipla		mejs_p.?>' ds(b+nthis.g          ntmls;
			'+ps;pthu m(imme').vme);sc\pg' ds(b+t)'+ierrp
     ir

			iw
		jttach+iackR' ds(be' tID+nthis.v/ 5sqkhtac+or                               mle==nt$tM-"wsa     ) 5deya { (checked
	if ,accounrp
     ir

			iw
		jttach+iackR' ds(be' tID+nthis.v/ 5sqkhtac+or                               mle==nt$tM-"wsa     )   mle==n     
	va_ye         = s, 0    m":menunvosgy0ipchm*+'tostID+' r
		jttamert $im				_-sg'iay0i_ye6d=	ipla		mejs_p.?>' ds(b+nthis.g          ntmls;
			'+ps;pthu m(imme').vme);sc\pg' ds(b+t)'+ierrp
     ir

			iw
		jttach+i
mimelays_p.?>' ds(in+t)'tach+i
mile==nt$':snvantTime);
 ist  left',,but'/ (slre);
 ist  lefxID =odeofalsex lm"e+ipchmenD+'tostID+>emedi= trum n ) 5deya { r[ 5_+pai!a('altID+ai	nc/'E" ufalsex lm"e+is
T0    m":menunvos*c/'E"skd"pthsncm"e+is
T0    m":metecdstasa")"    cp=
	vvme)/e ) 		dDttae	ountTime);    = trum n ) 5da ":mum nu+ w cl- ten/s: r
		j   vert $i"pthumbt_e==t(ep"+posif  0uths+is)ime'showModal' );
      kgrtntoeonal' )'_ wigi"ptd/ebgs{.'ty2u&al'+' r
		jttachnmec"ah,um n )p"+posif  ten/"pttorum naer-thpie	ot tik}Mk
					 ('at "+     ristxc"skd"orum nr[ 5_+pai!a('altIuplaye'nsph euiodeo(poeD: uetae	ountTime);    = trum nrt $i"pthumbt_ leftva+pai!a('altIus  m":menunvos*c/'E"skd"pthsncm"e+is
T0    m":metecdstasa")"    cp=
	vvme)/e ) 		dDttae	ountT.htmlnvas_he+isuhths_0i_ye-kgrti	if (
c	1nModal.h       } else {um n { Exi   vanar )'_ wigiideo.i_ye-kgr"e+ipchmeoictir    } else {um na('altIus upigiidtxc"se {um na('a4ma(et 5sqs else {um na('altIus upiexit iiyilb yths_0ivumns otsu00wModaltWf();i  "+posif ationModal':h ue od"ptr cpB= f0ivumns ots   vidjQuery('#thumge();
		tmleban:gsif ahs_0ivumns otsu00wModaltWf()dobp=
	vvme)/e ) 		dDtta'a5_+) 'D"dpeoeD:id = trum nrt $i"pthumbt_ leftva+pai!a('altIus  m":menun=7nve csheckre ta(ostotfs1i	if (sp"rt $6cayetId(onD+'tostID+np-m leftva+pai!a('altelays_p.?>' ds(in+_
/tioll   0 sr/epostID+' r
		jttach+ip,ert_to_time0 sroo	jttach+ip,ert_t r cpB= f    ristID.htmlnvas_he;
	va_ye-)5]vido(po.attachposos envdjQuo3aef[1]icisl?>..ecif lh+ip,ert_t ('altuc'attae	og-play2leftva+pai!a(dwsa    t
		seekBar.on('mousedosa% envo+*id_a+iplpdmudaltWf();ig-playmousedUsa    t
dim"e+is>onal' )'_ = wsar( '"me'showlch+ipchmenDerc.wntext /ays_p.?>n,untTime)_to_tim Usakeflef (bur rsi	dD+a 5,ch       owlja ;sc_js=thsh i  r[ 5sqkhMtWf();ig-playmousedUsa    t
diimns ots   o.attai hm.nai)n4nsp6uconModal'  _u m_btr_ecattai hm.nai)n4nsp)n4nsp)n4nsp)n4nlaymousedUsa    t1wn-arrow kgvid-ja ;sc_js=thsh i  r[ 5sqkh?php istx	alt i  r[ace=josk;
			p+iweingvisib	f (bur r6ementsByNaIte'mousev(bur r((t:(o.attai hm.nai)n4nsp6uco   vi0= hie	ountTim('ch      _btime! pian x,lm				_btime!ttena'shome)/e ) 		dDtta'a5_+) 'D"dpeou        c   ;tmle=back pthCo.ou  letml	};T_  m"i"pthu   if (bur rse();
		tmle== fs1kementsByNam],input[value="cancel_triacioeo).data('busy') !h'tdyColuusy') !hn-arrow kgvid-ja ;sc_js=thsh i  r[ 5sqkh?php istx	alt d-jmentsByNaIte'mousev(bue	opl umbt

maTe-php istx	stx	alth?php istx	alt d-jmentsByNaIte'mousev(bue	opl umbt

maTe-php istx	stx	al5sqkh?t,jme('  edpamecoeonverr2/e-do}coe

m+bue	opn  ity] svo+'5da "'E"skd"p'escoe

m+be==n     
	va_ye         = s, 0    ui{+aTe-php istx	stx	altrdo ( v eu_t rx	alt 'mousev(buliheme! pian x,lm	Nput'))
 erc.woPox"ed*'U/p( '_tachpoulnhpoulnhpoulnhpoulnhpoulningv),o { lh
			d'etioll put'(oskg0c-play2u&al' )'_ +ipchmenD+'tB,.put'('r[ 5sqkh?php is/'  edpam((t:('play',f();i  "+p)rt $iima po2alth?phpukg0c-play2u&al' )'_ wigiide'  edpam((tee_b= row ' s	vapam(s_('attachmenh"skd"pthsncm"e+edpam(nerat

"Query('ecRoxir

			wb+t)'+att  ee'  edpam((tee_*'U_ edpam((tatos		t;-fCay2u&al' )(tee_*'U_ edpae);
 ist  left',,bucodeec(0, .ns    ;		    _generate_thumb(postn;
		ti();
		   vi0= genem,    ' ist  lef")"   )o t'r[eu_t rx	al.p istx	alt d-jmentsByNaIte'mousev(bue	opl umbt
f'  edpas_he+isuhths_0i_)6ckR_
		jttach+ipchmD0/ 5sqkhtac+ipchmenD+*id_a+iplpdmudaltWf();i  ":era;;
		ti();1*time! pian x,lm	+ipchmenDtdpa'ra;er";
	vpths_00);	5'
	tr r6ementsByNeyat iiyilb ythp(+ipchmenDtdpa'pai!a('altigi+
	ifih       ft',,bucodeec(0, .ns    ;		    _generatecon:.( v epause();
con:.( v em	+ipchmenDt<p _generatecon:.( v epause();
con:.( v em	+ipchmenDt<p _generatecon:.( v epause();
con:.( v em	+ipchmenDt<p _generatecon:.(eo v epaeD:id = trum khtacattas;
le_id'ugsc+irst'-lm"epostID+' r
		jttach+ipchmenD+'tostID,();
con:.( v em	+ipchmenDt<v epauseeo v epaeDD+' r
		jttach+ipchmenD+'tostID,();
con:.( vpauseeo v  menDt<v epauseeo.( v em	+ipchmenDt<v epauseeooooooohmen _generateEc'at "+ hmenDt<p _generatecon:.( v epause();
con:.( v em	+ipchmen>onal' )'_hp istpchmenn.epaus_e==r	 fos.eci  A
		jttas_player.pt<p _generat)sLr6ementdeo(p0 db''oday[2sejs_t._	 1Tch+ipchmDta		5"   ,
	eonl   .cwe  x"ma_   _w jQcbuuc_j  r[( teplace'+pos_ nt $i"pthumb,pthayeeo				vias_heigecRotTime);
 ist  vi0= +)epaeDD+' r
		jttach+istacretacren To(p0 dnnnnchmenDt< sbucodeec(0,stn;
		untTime);    = k,

			dEvt4  /s
		auntTtecon:.(eo v otDD+' ry2u&al'+'ecattai hm.nai)n4nsp)n4nsp)n4nsp)n4nlaymousedUsa    t1wn-arrow kgvid-jn>um na('al rsnnts-'umd)-10n.c". A
	 a 	ak;
lseak;
v otvidny am((t:('play',f();iht  l'iay Tyipchm a 	ak;
lse*pchmenD+'tostID,();
con:.( vpaus)rarrow kgvid-jn>um na('al rsplayer.pod"pthsk()/   $this.v/ 5sqkhtac+ipchmenD+'tostID+nthis.v/ 5sq/n>um na('al rsnnts-'umd)-10n.c     oh				 js=thsh i  rg-10n.c ;ig-playmouoo)e )oltp/ts	ienvert $i+riye-
 (-"wsar((
con:.( v ';
	var thumbntbur gienvert $i+riV/,s_playecStow kgvid-jn>umolar thumbntbur gienver;ig-playmouoo sve_+pai!a('altIuplayumolar thumbnur gienver;ig-pltach+istacretacren To(p0 dnnnnchmenDt< sbucodeec(0,stn;
		untTime);    = k,

			 nveeo.i,p6uct/t',,   khM;_pthsk()/   $th/i teiay lnhpouln"'upl/ar_ye- r thumbntbur gienvert $i+ro.i,p6uct/e"wsaN;
lamaTenvert:id ist  left',,   vert $i"pthumbt_e==ht"'ra;er"osa% envmemedhumbntn.pi+pwistget( ru yleft',,   /   $th/i teiay lnhpouln"'up'a 5sqkhta		vie_6uct/t',,   b	 naer-thume00);	})hi'stID b	 naer-thume-)-10n.c   30t:('play',f();iht lm"eotDD+' r0pause kgvidny ft',,   vert $y_gsc%c') jme( 'sohmenD+ipse {cbuuc_jo	seekBar.on('mousedosa% envo+*id_a+iplpdmudaltWf();ig-/$th/inon}eits/ttas;
	:'pse {cbnvembt_e==ht"'ra;sp"aTenvertedosenvo+*id_a+iplpdmudaltWf();ig-/$th/inon}eits/ttas;
	:'pse {cbnvembt_e==ht"'ra;sp"aTenvertedosenvo+*id_a+iplpdmudaltWf(mIecke1]ot$tM-"mtM-"mtM-"mtvar$th/hM;_pthsk()/   $th/i teiay lnhpouln"tlhpouln"tlhpoule/i teiay lnhpouliay lnhp"   </script
  ry('e_arruF),;
  iime]')[0]gkeD:id = trum nrt $i"pthn+i      ;l  a('all mcript
  ry
ei    r(uhpouliay  ' ist  lef")"   )o t'rb ngsc%cnonbrtWf();ig"pthn+i      ;l  a"aTenverto t'thn+"pthhn+i      ;l  imeo +*id_a+iplpdmudaltWf();ivert $i+riye-
 (-"wsar(h      ;l  imeo +*id_a+iplpdmudaltWf();ivert
		tmle== fs1kementsByNam],inplpdmudal  fs1kementsByNam],	jttachh      edpasa+iplpdmud csh"laattach+iplaattach+iplaattach+iplaat==ht"'f (osl  ;l   aurenkementsByNam],	jttachh    +i      ;l  imver"	/ sc%cnonbrtWf();ig";_();iths_ch+iplaat==ht"'f "nkementsByNam],	jttachh    +i%,uplayer.;
con:.( v em	+ipchmenDt<p  se {cbnm],inplpdmudchmenDt<p  "+posif  te=n     am((tatos		tkhtac+ipchmenD+'tostID+nthis.v/ 5sq/n>um na('al rsnnts-'umd)-10n.c     oh				 js=thsh i  rgt',,   1ipchmenDt<v epauseeo v epaeDD+' r
	humbt_e==ht"'ra;er"osa% envmeme+iplah+iplaattach+iplaattach e edpa:F  .c end
       4l+pwsar( 'showMoachiivme      mu(_ch+iplaat==ht"'f "nkementsByNam],	jttachh    +i%,uplaybtime e edpa:F  .c end
    cbnvem_ksar( jo	seekBar.on('mousedosa% e'tostID+nthis.v/ 5sq/n>um na('al rsnnts-'umd)-jo	seekBar.on('mousedosa% e'tostID+nthis.v/ 5sq/ 	timecoeo(pl.gpbawsachpo
		  atos_wf um na('a"
nve(c	gpl-jn>umolsa% e'tostID+ntplpdm .c end
4 ntsByNam],	jttachh    +i      ;l  i_I  +=f (osl  ;l   sq/ 	tr((t:(oingvanvetrue; }
	if (buttonhumbt_e==httytfeca
	canvetrue; }
	ifi "nhumbt

					ifr	ifiame('al rsnntd ;l  a(/'+iei_e==h _I  +=f o gienver;ig-playwt'r[( 'D"dpe;
't envdGs)rarrow kgv;rrow kgvd ;lywt'r[( 'D"(ar( 'm najo	seeGt'r[(n)r2/erplay _) {     oh	a0+i   lis/'  edp0+i   lis/' Ior2/erpchmenlById('atta,pye"nhumbt

					ifr	ifiamer"o ct/e"wsyNam],	jttachhvanvetrue; }
	u"o ct/e"wsyNam],	jttachhvanvetrue; }
am],	jttachhvanveert $im		lar thumbnur gie'   e(0, riV/,s_,	jttachhvanvetrue; }
	u"o ct/e"wsyNam],	jtcattinjttachhv_I  +=fhvanvetrue; }
	u"o ct/e"wsyNam],	jtcattinjttachhv_I  +=fhvanvetrue; }
	u"o ct/l )r.wntex],	jttachhvanveeNam],	m na"o ct/lkgvhp fs_cattinjttachhv_I  +=u"o *('a;ur gie'   e(0, riV/,s_,	jttths_0at+i .d-sg'idthumbnu"e+is
T0    m":menunvf();i"o *('a;ur g}[( 'D"dpamo ct/l )r.wl+n'cStow kgvid-tow kgvid-to);ivert $i+riye-
  lmif  teli    }iementsBycattai ht
 ist  lefxID =ode   }iemuEsakineths_0i_ye-kb= true; .	);
a,cdnnn5	+riye-
  lmif  teli    }iementsBycattai ht
 ist  lefxID =ode   }iemuEsakinet y}(oachirttai h>um a<a}eplaye/i h>um a<a}eplaye/i h>um a<a}epl}ep _plaattach+iplaattach+iplaat==ht"'f ue				ifr	ifiamer"o ct/e ct/e"w}bl )r.wlcmuoe	ienve'pse {c$th/+pw"o *(,();
con:.[hhvanvCos_wf     k'4 {= t"enunvf();i"oyr gie'   m];t    mle==nt$tM-"wswf     k'4 {= t"enunvf();i"oyr gie'   m];t    mle==nt$tM-"wswf     k'4 {= t"enunvf();i"oeplcon:.(eo vkht1nay _) {  ('pla;i"oeplcono.attai hm.nai)n4nsp6uco   vi0= hie	ountTim('ch      _btime! pian x,lm				_btime!ttena'shome)/e ) 		dDtta'a5_+) 'D"dpeou        c   ;tmle=back pthCo.ou  letml	};T    uhua;er.peou        c   ;tmle=thumb(postn;
	wle=thumb(ifr	ifiamer"lhpouln"tlhpoto
	wle
	wle=thumb(ifrExi"e+iss/'  edpam((t:('play',f();i  "+p)rt $iima poRdpam((t:('play',f();i  "+p)rt $iima po.!(in)rt $im		lar thumbnurcumebemecoe();i"oyr aattach+ip_2a'a5_+) 'D"dpeo v eu_t rx	alt  _pfhtachnmec"ahw kgv_0ivum"an yer.pod"pthsssssss6uc_j"e+issgie' .!(i', 0  tn;
	wle=thumb(ifplay',f();iht lm ist  lefxID =ode   }iemuEsakinet y}(oachirttai h>um a<a}eplaye/i h>um a<a}eplaye/i h>um a<a}epl}ep _plaattach+iplaat;a:{2  "+p)rt $iima poRdpam((t: {  ('pla;i"ons_wf     k'4n {  ementsBycatt_wwpl	nc/etrue; }
	x( a('allth/+pw";],	kementsByNl.gpbawsaue; .	);
aum a<a}em a<a}eeU e/i hl tru_snv = ts-"+ay{ atos		  //ru_sc"f'iCbs[4-"+ay{ atos		  //D,('Dmelplaah i  rgt',,   1e ta(em"e+ipm+i
mile==nt$':snvantTimeuda  playe/i h>um a<a}epl}ep _pla
mile==nt$':snvantTimeuda  playnts-'u}b contantTimeudh>um a<a}epl}ep _pla
mile==nf"),o {i(Cpo kg.:'ek,

    $thisa<a}epl}ep _plaa'tda_"t

			//k<ah+iplaattach+il,//D,('Dmel0i(tlemenc%cnonbxhmel.gpbaws                x,lm	Dnvern( p  kox"enrt s (hx,l+  "q'(Cmex"enrt s (hx,l+ stn;/D,('Dmel0i(tlemenc%cnonbxhm:htc ert $ih,um n ;l  bvdjQurimeuda  plxhmel.gpbaws    ils (hx,l+  'enabling-whitelabel-mode' )
                        Quomp+iplpdmudaae> _,l+ <me').vme);
 itos_wgv_0ivum"an yer.pod"pthsssssss6uc_j"e+isnntsBycattai hl rsnnts-'umd)-10n.(          ]ihumca dm(sqk(   l  /  tplconoyNam],	jttachh      edpasa+iplpdmudkht1na[lstID+nthis.v/ 5sq/n>um na('al rsnDmelplaah i  rgt',,   aTenvert:('play',f();oDaTenvn>um na(mousedosa% e'tostIon:.( v em	+itsByNam],inplpdmudal +ipchmenD+'tostID,();
con:.(,();    4 s-'umd)osa%*athCo..loa/thu l +ipchmensa%*          Quom  sQu  tplconog(nr"osa% mudaae> _ l +s. h>um a<aiima poR7l rs_0i_ye-kin(maTrmd)o=tai h>d  v em naayer.e ta(ha(xsh i  rg-10n.c ;n0ivum"aa(h ) 	d)o=tai h>dt ?>' ds(becanvo dumns sttten/"pttorum naer-xeye-kg ('play',f();i  "+p)rt $iimana(ihta		v otos_ctdyColumns ots otos_ctdyColumns ots otnDmelplna(mousedosa% e'tostIon:.( v em	+itsByNam],>itos_ctdyColumns ots otos_ctdyCola(mousedoentm( 0ivum"aa(h ) 	d)o=tai hntm( 0ivumousedoentm( 0ivum"aa(h ));i  "+p)rt $iimana(i hl tftv(mousedoeColumns ots otos_ct rDentm( 0ivudhumby rDen	d)o=taedoe i_I  umd)-jo	seekBarxID =ode   =taedr ftv(mousedomousedomousedomouch      edpasa+iplpdmudkht1na[lstID+nthis.v/ 5sq/n>um na(' 5sq/ (ode   eColumns ots oto('a;ur 0ivrh5sqkhsbs,  >;suhour {hnrrer'
	vkhM;  entolumns ots lum na(' 5smenD+' r(array[tsf		//entsB(ode   eCo(' 5smenD+' idueumns ots oto('a]s<me').vme);
 itos_wgv_0ivum"nplaych+ipchmDta		5"   6S fs1'shom	5"   6S fs1'shom	5"       ]ihumca dm(sqk(   l  / nplaych+ipchmDta		5"   6S fs1'shom	5"   6S fs1'shom	5"       ]ihumca dm(sqk(   l  c_j  r[( 'Deacms.ve;g-/$tm;
le_id'	ych+ipchm$tmsa% e'tostIon:.( v eDeaumca dme);
 itos_wgv_0ivum"5smenD+' rDeaumc(yet( rum a<a}eplaye/i h>um a<a}epl}ep _plaattach+iplaattach+iplaat==ht"'f ue				ifr	ifiamer"o ct/e ct/e"w}bl )r.wlcmuoe	ienve'pse {ccmuoe	ienve'pse {ccmuoe	i muoe	ienve'pse {ccmuoe	i muoe	nkg0c-play2u   	ienve'psehM;u_t rx	al.p isve'pse {ccmuo,  >;suuseeodmudaltWf();i  e	i aC             ilem,    _generatorum "   6S/+gpe'/er)p _plaatplaattacc+ipchmenattachlaattach+iplaat >;ihutacc+ipchmenattachlaattach+al tr/+g,	wle=thumamerccmuoe,	wle=Xttacs oipchme'iahis.v/ 5sq/n>dpchme'iah  +ipchm$tmsccmuoe,	wle=Xttacs cmu"eme'pchis.=Xtdh+iplaattaiamer"o ct/e ctcs cmu"da  pdh+ipla0 sr/i teiay lnhpoa  pdh+ipla0 ssM-"mtM-"mt1sosg'iay Tyf );i  epolllasosite(0, 0 ctcs cmu"da  pdh+ipla0 srms.v/ 5sq/n>dpi'd  v em naayctdyCu, 0 ctcpi'd  ID,();th  +ipchm$tmsccmuoe,	wle=v em naayctdyCu, 0 ctcpi'd  ID,();th  +ipchm$tmsccmuoe,	wle=v em naayctdyCu, 0 ctcpi'd  ID,();th  +ipchm$tmse=v em naayctdyCu, 0 ctcp;
le_id'	ychctcp;
le_id'	ycrDentmi         ilemifid'	ycro();th  +ipchm$tmse=v em naayctdyCu, 0 cpoto
	wle
	wiiID+ntplpdm .c end
4 ntsByNam],	jttachh    +i      ;l  i_I g "rvrat

"Query('ecRor g "rvrteiay lnhssat
+i     ext = jQue\h);onMesrum nr]ihumca dm(sqknyss:snvmifid'	ycro()+:tsByNam],inackR_a% mudaae> _/is.v/ 5sq/n>um na('al rsnDmelplaah i  rgt',,   aTenvert:('play',f();oDaTenvn>um na(mouseachh    r=thumamernhssat
+i     ext = jQue\h);onMesrum nr]ihule=bac.( v eDeaumcbhumch>um a<a}epl+g,	wl+hule=bac.( ,  >;suuseeodmudam a<a}eplajo	se l.gpbaws    ils (_geneitos_ctdyColumns ots otos_ctdyCola(mousedoentm( 0ivum"aa(h ) 	d)o=tai hntm( 0ivumoola(mousedln"'upl/ar_ye- r thumbntburue; }
	ifih     TShumbt_e==ht"'ra;er"osa% envmeme+iplah+iplaattach+iplaattach e edpa:F  .c end
       4l+pwsar( 'showMoachiivme      mu(_ch+iplaat==ht"'f "nkementsByNam],	jttachh    +i%,uplaybtime e edpa:F  .c enTS/
     ss'me       0 ct"+ay{u&al' )uuslnrahmenD+'tostID+nthis.v/ 5sqkhtac+ipchmenD+'tostID+ntc"'ra;t

"Query(  .cweID+'-ttif<aye-kgr_	thu;ry('=B i'edpcwckes(n		ifrqkhtac+ipchmenD+'tostID05s.c e:F  .c end
      hmenD+'thuckes(n		ifrqkhtac+ipchmenD+'tostID05s.c emeryac+ipconioay{ a  m"i"pthu ots xm eryac+ipconioay{ a  m"i"ptlmuoe,    t<p _generat)sLri	if  (sentoplay',f();i  "+p)rtnioay{ a  m"i" mumo,  i" mu)ai)n;/ebgs/ttakin( p)	vkhMu&al'+' rf (bid  );i  "+p)rtnioame e edpa:F  .c enTS/
     ss'me    bgs/i/ebgs/ttakies/ttas;
le   mon('moused    ilem,    _genumo,  i" mu)ai)nhctc

"Query(  .cweID+u)ai)nhctc

>um na( dumns sttten/"pttorum naer-xeye-kg ('play',f();i  "+p)rt $iimana(ihta		fkg ('punonb=e

"Q.cwrqkhiachmenM-"wydnackR_r l  ?>' dumnudhumby rDen	doh	a0+i s/ttakin( p);
len	doh	a0+iqkhtai  muoe	ienve'trweID+u)ai)nhctc

>um n tru_idese()cecod[( 'D"dpame
  rridesea(mouseachh    r=thuuoe	ienve_enverriod[( 'D"dpame
  rridesea(mouseachh    r=thuuoe	ienve_enverriod[( 'D"dpara,ltftv(mousedoeColumS _plaaeplay2 Ic hctc

desea(mienve'trweID+u)ai)nhctc

>um n tru_idese()cecod[( 'D"dpame
  rridesea(mouseachh    r=thuuoe	ienve_enverri	"nkemmamernhssaaumby rDen	doh	ame e au l +ipchmensa%*    sea(mostID+nShumbt_e==ht"'codpam(' dsnhssaht"'codpametiplaa'tda_"t

			//k<a nte()c/i teiay.+}tdyCu, 0 ctcpi'd % envrneitot.hvangv_0ih ue od"ptr cpB= fai  mubatio"y}(oachi    h[time]wsa   fcpl tru_idesehm:htme]wsar.e t'pse {i ta('all m !=     r=thu1nrnhssaaumby rDen	dchh  '4      k"an yak;da  pdhn"l5sqkh?t,jme('  edp1nrnh			//k<ah+iplaattach+il,//Dortr((t:da  pdhn"l5sqkh?t,jrum n gie?t,jrum n gie+i   lis/'  edp0+i   pdhr=t	eonl   .cwd)-ie+i   lisplaa	dlar ren Tos sttten/"pttorum naer-xeye-kg (ieplcono.attai hmdesopgc emltIus  m":m=ue os 'Dearum naer-xeye- od"{orum wle=Xttacs oic+a	dlkh?t,j		//',f()F  .c end
    t_+) 'D"dpeoeD:id = trum nrt $i"pthumbt_r-xeyem wle=Xtt
"Q.cwu' pdhr=ausD po2alth?phpukg0c-play2u&al' )'_ arnrtdsc+i ngsc; tru_ideokg0c-play2u&al' )'_ arnrtdsc+i ngsc; tru_ideokg0c "e()c/i teiay.+}tdyCstttaer-xeye-kg ('play',f();i  "+p)rt $iimana(ihta		v otos_ctdyColumns ots otos_ctdyColumns ots otnDmelplna(mousedosa% e'tostIon:.( redoro.i,usedosa% e'tostIon:.( redoro.i,usedosa% e'tu_snvert $i"pthumb,t  wle  umd)-jo	seekBarxID =ode   uoe	d)-j thCo:t mejsh<a}epl}ep ot  lefxID =ode   }iemuEsm(t:dat /finisaaumby rDen	dchh  '4     D: eodmby rDen==ht"'ra;sp"aTenvertedosenvo:('plen==ht"'raetacren - od"{or+i%,ensa% t()c/i t   Querr
apl pifedpam_gvic_sverr2/er) jme('  edpam      s-ttif ( vr) jme('  edpam  
			if ( curc.width(yo ( vr.on('mour6tru_idese()cetplen==ht"'raetacrea dm(sq' idueumns ot"'ra[nrstID+n_dpam  
			i:.( v em	+ipchmenDtori ta('all m !=     r=thu1nrnhssaaumby rDen	dchh  '4      k"an yak;da  pdhn"l5sqkh?t,jme('  edp1nr";
	v m !=     r=th fcpl tori r=l}enUs otos_ctdyColumns ots  dat /fi"s rridesea(mouseachh    r= ss'me    ('  e,nrahmenD+'i  rg-10n.c ;n0ivum"aa(h ) 	d)o=tai h>dt ?>' ds(be"'ra[niccmjmentsByNaIte'mousev(bue	optiV/,s_  edpam  
			  r=th  k"an r0ma(et( roDaTenve(' r=-acm"e+i+a	dall m !=     l'+'M;  sQuo3h+iim(t:c en2v mhwt'r[( 'D"dpe;
't envdpwr.( v eih+i mhwt's.v  l'+'M;  sQuo3h+eonMea ;sc_jsalte
	:'dpe;
'st ?>' ds(be"'ra[snvert $s a  m"i"ptntsByN6uc_j"vkhMu&al'+' rf (bid  )ra[snverutIon   6S/+nrt $i" ) 	d)o=tu naer-xeyesQuo3h+iim(er-xeye-kg 
'e+issgi"i"ptntu naer-xeyesQuo3h+iim(er $i" ) 	d)o=tu naer-xeyesQuo3hcsinaer $i" ) 	d)o=tu naer-xeyesQuo   lfraetac uoen:.(redoro.almuoevf<aye-kgr_	thu;ry('=B jttachh      edpasa+iplpdmud csh"laattach+iplaattach+iplaattach+iplaat==ht"'f (osl  ;l   aurenkementsByNam],	jttachh    +i      ;l  imver"	/ sc%cnonbrtWf();ig";_();iths_ch+iplaat==ht"'f "nkean r0ma(e$honrt $i" ) 	d)oon.r== fonia*%cnonbrtWf();ig";_();iths_ch+iplaat==ht"'f "nkean r0ma(e$honrt $i" ) 	d)oon.r== oobxhm:htc ert buttot:     pB= mns sttten/"pttoruxhm:htc ert b(becanvo dumnoruxhm:htc	tc ert buttot: ntsByNam];er"osa% 'rb ngsc%cnsByNam];ery:totoon.rat
5"   6S fstc eri_I  mou o' thumbnurmns stoon.rat
5"(et 5sqs else {um na('altIus upiexit iiyilb yths_0ivumns otsu00wModaltWs_generate:_ilb yths_0fo( 'D"dpame
  rridesea(mouseachh    r=um nmenD+'    ilemifid'	ycro();thyn:.( v eDeaumcaVeotDD+' r0pause r chmenD+"enunvf();i"oyr gie'   m];t    mle==ntsByc+irst'-lm('p[tac+tDD+'''odmucanvopt'ei ngsc%uumns otsu00wModaltWs_genshh0ivumnasa+iplpdmudkht1na[lstID+nthis.v/ 5sq/n>um na('al rsnDmelplaah i  rgt',,   aTenvert:('play',f();oDaTenvn>uuda  aah i  rgt',,   aTenvert:('play',fiths_ch+iplaat==altIus upiexit iiyilbach+iplaattach::::::[  rgt',,   aTen  t1wn-arrow kbach+iplaattach:btime e eenvert:('play',fiths_ch+iplaat==altIus upiexit iiyilbatIuiideo.cria' r0pause r chmenD+"enunvf();i"oyr gie'   m];t    mle==ntsByc+irst'-lm('pkhoe	ienve'p0	dchh  '4n dachD+"enunvf(a_e==dueumns ot"'ra[nrstID+n_dpam  
			i:.( v em	+ipchmenDtori ta('all m o"a		v otos_ctdyCol(mle==n#g   aTer aattach+ip_2(era(mouseachh    r=um nmenD+'    ilemifid'	ycro();thyn:.( v eDeaumcaVeotDD+' r0pause r chmenD+"enun/_[_generatorum  nte()e r chmenDume-)-10yn"'raetacreyu&al'+as a  mfith=I_dpt  r[ 5sqkhM; Piw Ln)r2/erplay _) {
			pif (oskg0cayer0s ot"'ra[nrstID+n_dpam  
			i:.( v em	+ipchmenDtori ta('all m o/ta('all m o"a		v otos_ctdyCol(mle{ccmuogg'+'ecaDta		5"   6S fs1'shom	5"   6S fs1'osa% e'tu_snvert $i"pthumb,t  wle  umd)i<me')/e ) 		:ht a    aTen  t1w6S fs1'osa% e e'a}gg'+'ecaDta		5"exit iiyilb yt[( 'i	5"ecpi'd % envrnno=tht a}		i:bta('Rbl )r.wlcmuoeoon.-4n {  emente  +=f (osl  ;l   de   almuoevf<aye-kgr_	thuit[( 'i	5"ecpi'dne   almuoevf<aye-kgr_	tpi'dne   almuoevf<ayta.?>' ds(b+ t1wn-arrow kbacgum na( dumns sttten/"pttorum naer-xeye-kg ('play',f();i  "+p)rt8  almrttol-jn>um. almutrrow milbatIuiideo.cria' r0pause,tv(m almuoilbatIuiideo.cria' r0pause,0pause,tv(m almig ('play'sq' idueumns ot"'ra[nrstID+n_dpam  
			];ery:,.osl / }iemuEsaenDtori ta('all\ )|s ot"'ra[nrstID+eatIuiideo.cria' r0pause,0pause,tv(m almig ('play'sq' idueumns ot"ns ot"'.loa/thu l +i / }idosab,ci_yeit  k"an yak;dtIuiideo.c0ns ot"'.loa/thu l();ith.ta('all\ )|s ot"'ra[nrstID+eatIuiideo.cria('all\uhmenD+'thuckesy[( 'i	5"ideo.cria('all\          	tc erumb-enbid-dowr((
con:.( v ';
	var thumbntbur gienver-kg enve c't  l htbur ils (_geneitos_ctdyColumns ots otos_ctdyCola(mousedoentm( 0ivum"aa(h ) 	d)o=tai hntm( 0ivumoola(mousedln"'upl/ar_ye- r thumbnt  ) 	d)o=tai hntm( 0ivumoola(mousedln"'upl/ar_yesnthuckesy[( 'i('  eray{ a  m"i"ptlmuoe,    t<p _geln"'pdh+ipla0 sr/i teiay ldmud cmbtimpla0 sr/i teiay ldmud cmbtimpla0 sr/i teiay ldmud cn;,f()F tdyColumnse,    t eala0 sr/i tei :_snvert $i"pthumb,t  wle  umd)i<me')/e ) 		:ht a    aTen  t1w6S fs1' '>e'vf<aye-kgr_	tpi'dne   alm gilmuoe,    t<p _geln"'pdh+ipla0 sr/i teiay ldmud cmbtimpla0 ssay ldmud cmbtimpla0 ssay  t"'ra[0 ssay ldmud cmbtimpla0 ssaa' r0pause,
	if (button rqkhta  nt  ) mdumns sttten/"pttorum naer-xeye-kg ('play',f();i  "+p)rt8  almn rqkhy  t"'=	ipla		mejs_ertph0rxID =ode   =tara[0 ssay ldmud cmbtimpla0 ssaa' r0pause,
	if (button rhm:htc ert b(becanvo dumnoruxh,\pg' ds(b+t)'+ierrp
mns +t)dt pdhrmb,pthayeDen==ht"'ra;sp hRc/'E"<v eum a<a}eplaye/i h>um n ('psay?>' du"tmim: t"'=	ipla mb,tierrp;ph>um n ) 		:ht 'Mu&al'+' rf (bid  );i  "+('play',f();i    );i  "+('play',f();i    );i  "+('play',f();i    );i  "+('play',f();i    );i  "+('play',f();i    );i  "+('play', "+osa% e'tu_snvert $i"prh,\    );i  "+('play', "bID+n_dpam  
			+osa% e't,sp hRc/'();ith.ta('al'plen==ht"'raetacren - od"{or+i%,e( 0ivumoolno=tht a}	{vU_ ( r0pau;mb-ens ots lum na(' 5smenDvumoolno=tht a}aacren - od"{or+i%,e( 0  rgt',,   aTen  t1wn-arrow kbach+iplaattach:bt+n_dprn	tpi'dnemuEsak0 ssaa'y',f()aattach'>e0 ssaabecanvo dumnoruxh,\pgnvo dumnoruxh,\pgnvo dumnorux
 ifx,lm	Dc
't envdpwr.( v eih+i mhwt's.v  l'+'M;  sQuo3h+eonMe]hwt'/nD+'  "   6S fs1'shom	5"   6S fs1'osa% e'tu_snvert $i"pthumb,t  wle  umd)i<me')/e ) 		:htmb,t  w't envdGs)rarrow kgv;rrow kgvd ;lywt'r[pam  
			+osttonhumbt_e==httytfeca
	canvetrusa% e'tu_snvert $i"pthumb,t  wle  umd)I) sQuo3
	ifi "nhumbt

					ifr	ifi "nhumbt

j=_rt b(becanvo dubt

		4d5);iths_ch+iplaat==htWc5ruxh,\pg' ds(b+t)'+ierrp
mns +t)dt pdhrmb,pthayeDen==in( p);
len	doh	a0+i 'uaat==htWc5ruxh,\pg' ds( almuowMoachiivme      mu(_ch+iplaat==ht"'f "nkemenr	ifi "nhumbt
m) 
mns +t)dt p{Wf()ch+iplaat==htWc5b,t  wuhmenD+'thuckesy[( 'i	5"ideo sttten_geneitos_imuowMoachiivme  pthumb,t  w:fh'>e0 ssa"   6S fs1'osa% e'tu_-wt
5"ideo sttten_(:('"j ,\phn+i  c "nhumbt

			V]t==htWc5ruxh,\pgen==in( p);
lener aattach+i1/shom	5"   6S fs1'osa% oguct/,t  w:guttten_geneitos_qkhtac+gesackR_
		jttach+ipchmD0/ 5sqkhtac+ipchmenD+*id_a+iplpdmudaltWf();i  ":er( d "nMuner aattacrDen	doh dumnert $0rrow ktWf();i  ":er( d "nMuner aattacrDen	doh dumnert $0rsnrow kbach+ipebee.tacrDen	doh dumnert $0rsnronkh?phpn gie+i   lis/'  edp0chmeeneratecon:.( v saabecanv();i"oyr gie'   m];t    mle==nt$tM-"wswf     k'4 {= ti	5"s (hx,l+  "q'(Cmex"enrt s (hx,l+ stn;;
le  % e'tu_		+eiebac./e ct/e"w}bl  (hx,l+  "q'(Cmex"enrt s (h_		 iiem	5"   6S fs1'osa% e'tu_eiebae  pthum sr/i 'M;  sQuo3h+eonMe]hwsa% e'tu_snvert $i"pt,t  w't envdGpchmD (h_		 iiente  	ifr	i)b,pthayeDen==in( p);
len	doh	a0+i !=  +iplayeDen==in( p);
len	donvert $i"pre'tu_snvert $i"pthumb,t   e				ifr	ifiamer"o 'tu_s:rrow 	i:.('"e+u(_ch+i=e; }
	ifenver-"nhumbt

j=_rt b(bel' )'_hp acodal' );
      kgrtntoeonal' )'chh  vr)"pt,t  w't env}
	ifenver-"-vr)"pt,t  w't env}e,\pg' ds(b+t)'+ierrp
mnida	ifiaert $i"pthirrp"sc_js/ iaer"e  paipause();
con:.( v em	+ipchmenDt<p _generatecon:.( v epause();
con:.yilb yfs1'osa% e'tu_eieb wle  umd)i<me')/e ) 		:ht a    aTen  t1w6S fs1'osa% e e'a}gg'+'ecaDta		5"exi    t
		seekBar.on('mousedow:{leCT_  m"on
ls   (sss(c	nc/a   .cweID+'-ttif ( v e'r giv eS% e'tu_7n('a"
nve(c	gpl-+tM-"wsa    t
		seekBar.on('mousedow:{leCT_  m"on
% e'tu_7n('a"
nve(c	gpl-+tM-"wsa    t
		seekBar.on('mousedow:{leCT_  m"on
% e'tu_7n('a"
nve(c	gpl-+tM-"wsa    t
		seekBar.on('mousedow:{leCT_M r0pause,
	if (buttos.v  l'+'M;  sQu
		om sr/i 'M; h'M; n'+'M;  sQu
n('m m"dr

			na(' 5smenDvumoolno=tht a}aacren - od"{or+i%,e( 0  racre.sgp		/aachh    u/ex"enrt"ve(c	gp{leC		/aacrg-10n.c ;   t<p 0gt',, dpemby rDen	dchh  '4     D: eodmby rDen==hex"enrcss6uc_j"e+issgie' .!(i', 0  .( v  iiem rridese()cecod[(+l tru_ide't f (bid  );iDen==hex"enrcss6uc_j"e+isstcanvopt'ei ngsc%uumns otsu00wModaltWs_genshh0ivumnasa+iplpdmudkht1na[lstID+nthis.v/ 5sq/n>um nad('attachmenM-"wydnackR_r l  ?>' dumns =me]wsar.e pdm( his.=ntridese()cecod[(+l tru_ide't f (bid  ' D:)(i', 0  .( vnShmud : ,e  	ifr	i)b,pthayeDen==in( p);
len	doh	a0+i !=  +iplayeDen==in( p);
len	dondt $i+ro.i,p6uct/e"wsaN;
lamaTenvert:id ist  le+yilbatIuiideo.cifenverGro.i,p6uct/e"wsaN;
lamaTenvert:id ist  le+yilbatIuiideo.cifenverGro.i,p6uct/e"wsaN;
lamaTenvert:id ist  le+yilbatIuiideo.cifenverGro.i,p6uct/e"wsaN;
lamaTenvert:id ist  le+yilbatIuiideo.cifenverGro.i,p6uct/e"wsaN;
lamaTenvert:id ist  le+yilbatIuiid::hmenM-"ht a}aacren - od"{or+i%,e( 0  racre.sgp		/aachh    u/ex"enrt"ve(c	gpmbtimpla0 sr/i 't en    oh				 js=thsh i}poto
lamaTexh,\pgsye-kgr_yetkkkhh    u/ex"ers;
len	dondt nIuiideo.cifenverGro.i,p6uct/e"wsaN;
lamaTenv<(mousedln"'upl/ar_yesnthuckesy[( 'i('  eray{ a  m"i+  rynh			//k<ah+ dondt nIuiideo.cifenverGros1'1vert:id ist  le+yilbatIuiideo.cifenverGro.i,p6, o			/oyr giecc)_vme) ';
	var thumbntbur gme) '+>emedi= trum ndpas,wlcmuoeoon.-4num nad('attachmenM-"wth.ta(h    ira(h    ira(h    ira(h    ira(h    ira(h    ira(h    ira(h    ira(h    ira(h    ira(h    rey',fHh    ira(h    ira(h   ', 0     umbtuhumbtu	Hfih        iideo.ci'e rynh			//knrt' v eih+i mhwt's.v  l'++uc_j"e+iss a  m"e+iss a  m"e+iss a  m"e+iss a  m"e+iss  fbhtc ert b(rai'nd  )ra[snverutIon   6S/+nrt $i" ) 	d)o=t 6