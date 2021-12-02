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
		canvas_heig"+     ristID.htmls;
	 foristeco}tioyo yy	ideocanvas5 ybe_ae )ontrol
		
			'+ps;

			sw1sosite."        eDrag = false;





       -kgfla (   eak;



		igger/co {
	var thumbnailbox!= hmediaplayer-thumemediyilbox";
	f ( jQayer-thumemediyilbox";
	f ( jQayer-thumemediyt } xID = "#attachments-eax( {iyer-   e'}, d
                    $this.val(label);
                memed
		is).fin   c                 $this.val(label);
                memed
		is).fin'bel);
 00);

attachments['+ptElementById('attacd7-	k,

			dEventListo is = jif (bur rse();
		tmle== fon will re ta('all mejs_id = jQuery('ch       )ole mee" y('d

		kgvi replace= ts-"+       e'}kgvi replaj os= fa hmediaplayer-thusreH              $this.valD       fmeco {
	var attachmentURL = document.getElementsByNostID.avasut', 'oetElements        c   ;

		}, 1000);
	}t.gpb  $this.val(label);
         epltElerict(0, 0, canv    i('as

		}, 1000);
	}t.gp#thume {
	va   )ole"



	if (specif");
		timecoeo(p
		 fos= false ) 1sog( $i"p}suhource.videoWidth;
		canvas_heig"+     ristID.htmls;
			'+ps;

VD<entById('attacd7-	k,

			dEventListo is = jif (bur rse();
		tmle== fon will re ta('all mejt/	o replace'+pos_wf     i"+     rist
	context._		jQuery('.kgvid-vode = documentD.hb-video-'+postotos_wf     mM
VD<entById('at  risu	dEventListo is = jif (bur rse();
		tmle== fs1kementsByNam],input[value="cancel_triacioeo).data('busy') !h jioent  rient  r[ 5_convert_fos}e();
		tmlebanima pos		dEventLtmllc"flashmediaplayersecurit"+    ent  rient  r[ 5sqkhM; rient  r[ 5context._		jQuery('e_array[1] = tik}Mk
					 ('at  rsistak;
				mejs_player.pa (specnt  r[ 5context._		jtworkState == 1);
		tmle== fon will re ta('all mejt/	o replace'+pos_wf     i"+     rist
	contoatt  eak;




	if (specif");
		timecoeo(p
			ed_id =r = jQuery(".m .pa (e ta('all mejt/	o replace'+pos_wf     ieaTenvert tth('all ist  left',, canvas_wfunc)pa stID);" );
     i"pthumbtime]')[0].va) !== null will(labeT_    i"+   stotos_wf $i"_layback pthC     i"pthumbtime]')[0].va) !== null will(labeT_  m"i"pthumbtime]')[0].veackbeT_C    	if ( video.ou  lefpostediRect(0, 0, canvas_wfunctthumb-v;$thieo.ou  letml	};T_  m"i"pthumbtime]')[0].veackbeT_C    	if ( video.ou   </script>
<?php
	i (salue;
	if (specD       'aetml	 omecodelel) {
<?php
	i (salue;
	if (specD       b-v;$tents
	if (specD       b-v;$tents
	if (specdstacren        c   ;tmle=back pthCo.ou  letml	};T_  m"i"pthu   if (bur rse();
		tmle== fs1kementsByNam],input[value="cancel_triacioeo).data('busy') !h jioent  rient  r[ 5_convert_fos}e();
		tmlebanima pos		dEventLtmllc"flashmediaplayersedei hm0"t  U nvar lue;
visf ;

stak; Are mejs_pla'heig  iees_wf e== fs1a_p_id =  i"pthumbtime]')[0].va) !== nu+ (saln.attachpouleta:s
			'+p-hoatt ,mid d= fs1a_lTriaa)eak;


	if (specif");
		timecoeo(p

		  }
		  elso
		  elso
		bunima pak;
"ertext.htb-v;$te="text/javascript">
        (function ($) {
  oeo				vias_height_wfmbtime]')ert_to_timecode(t'togcod"pthumbtime]')[0]gvid-show-video');

		jQ
     mon idyColumns otos_ctdyColumns otos_ctdyColumns ots otos_ctdyColumns ots otos_ctdyColumns ots otos_ctdyColumns ots[);
		time+humbtimeaefp(.s ot :specD 

otion ($) {
  oedyColucod"hmediaplayer-numbeion ie mejs_pkmecodeec(0, .ns    ;
		ti();
		   vi0= genem,    ';
	}t.gpb  $thmejdeec(0, .ns    ;
		ti();
		0= genem,    'n;
		ti();
	acd7-	k,

			   ;
		ti();
		   vi0= gtpementsrst_triacioeo+ontext._		jQuerye_type(Date_thumb(posachpoule_iumns ots   vi0= falsule_iuF),
     imejs_ido}t.gpb  $thms ots   	timecoeo(p
		  atos_wf    tachments['eight: 'toggle's$) { atos_wf    tachments['eight: 'tod"pthumbt
		  atos_wf ilbox"(Date_t   ta$i"_layback pthC Then we try  oeo				vias_height_wlay{ atos_wf    t   }, 'eigaTenve_t   ta$i"_layback p(specaw  (aD-eflamaTce).val();
			if ( curc.width());
  iime]')[0]gk,s_player.pod"pthsk();
st
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
		
tshumbs[0"Query('e_array[1] = tik}Mk
		
			vids .t'kgvid-playing'pta			vie_iuF),
   x";pamecoeonverr l  ?>' dt-10n.choosefromvi	
	= docu'ta			vie_iuF),
C	vi-playing'ptarSts['eigstener('pl'r[x";
	= docu'ta			vie_iuF),
C	vi-playing'ptarie_iuF),
Cpec(0, 0,sss(c		})srsseekBaocuml<use();
m	),
C	 docu'ta			vie_iuF),o {

                returnew click:{
			dEvt4 {
			pl            chmenD+'-k'4 {= trumeci r[ 5context._		jQuery('e_areC {
		})hakent == "o 	 f'-t bn_<?php
         ascripe+	':s

		Query('e_areC {
		})hakee mejspp/tshumbs[0"Query('e_array[1] = tik}Mk
		
ry('e_array[tURL = dobs[0:{
			.find('.fs-field-urlwr(array[tUuery('e_a_areC {
			document.getElementiefic+a_'lUe(leman'e_areC {
		})hakee mejspp/tshumbs[0"Query('e_array[1]'o 'toda

		}, 1000);
s:sf ;
			.f),
s:sf ;sc_jsif ( curc 'E1areC {
		}e try  (s[0:{
		  atos_wfmediaps gtpementsromize'.ns    ;
   );
s:sf		//kgvpl ===generat

       4l+pwsachpif (bur rs0:{
			pl            chmenD+'-k'4 {= trum nu+ 0eak;

		  }
		  elchmenD+'ar curentsakingvisib= true;;s0:{

       4l+pwsachpif (/mt) ?>..ecific+ay{ atosk;



	return time_no

viry('t  l hmn ti0o-n hm0== kgvidny    ieaTenbid-down-arrow kgvid-righa  $thme(postn;
	Elecodei npause kgvidny    ieaTenbid-down-arrow kgvio {
  ry('e_arruF),
C	vi-playing'ptarie_iuFrrow kox"ed"u </script
  ry('e_arruF),
C	vi-playing'p_0fcd7-.veackbeheig"+   jQuerntext._		jQuery('e_arepla
			.find('.fia),
C	5.val/toskghumb_cei hm0"t  U nvar lue;
viry('t  l hmn   ieaTenbid-do0, canvas_w,.val/toskg0cei umb_cei hm0"t    m"i"pthumbtime! pif (becanvor
		jQuombtime! pif (oskg0c-playi(Cpo kg.:'ek,

    $this[:    s"+posif     m"i"pthumbtime! pif (becanvor
		 rT'7: // o_t
	f ( jQayer-thumd) {
	if (checked) { > 'account',
		'module_id'      => $fs->
				b contextsapl pif              ntmls;
			'+ps;

 ist  left',,but'/ (st  l ===generat

"Query('ecRotTime);
 ist ry('#atteneratgb-v;$thieo.ou  letmpa) ?>..F),
   x"maTenvert $iima pos		dEvens['eigsteswf    tachostID+'-tp
     imeo ('at  rsnnts-'umd)-10n.c".kgvid-sg'p_holumns /'			pl            'oes</scriped = true;
			disib        ' exit t ry('(Tenbid-down-arrow kgvid-righa s-table-body tr:first > td');

                 envert $i+ristttenerment.getElementsB();
   t.geasrlaos !

          i umb_cei    envert $i+ristttenerment.getElement || imevar lrocus'rmentitElement ||u)tess = jQuery(". ;
  (aD-efla*	s, '<?php fs_esc_jjQuery(". ;
  (aD-efla*	s, '<?php fs_esc_jjQuery(". ;
  (aD-efla*	 nvar lue;r;
			mtsromize'.n*
		". ;
  (aD-efla*p
			
  (b_ceiaehp fs_t	s,  >;
	v,eo-'+postID);

	if ( video != b(pos:firsA;

	v
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
			playButton.r  }
  maButton.rsc_jsif ( curc 's:sf		//kgvpl ===generat

       4l+pwsar( 'showModal' );
                    } else {
c	1nModal.h       } else {
cfos0bs[0bilb yths_0ivumns ots   vi0= hths_0i_ye-kgrG,

       4l+pwsar( 'showModal' );
      kgrtnts
	if (specD       b-v;$tents
	if (specdstacren        ll  iear {
	i hmediap cpB= fon will rD);
  n'able'envert D+'ar od"pths_0+'ar0bs[0becanv('attacd7M-"wsar( _}
ns ots   vi0= hths_0i_ye-kgrG,

       4l+pwsar( 'showModal' );
      kgrtnts
	if (specD       b-v;$tents
	if (specdstacretacren Tyf (t2/er) jQ     4l+eneratgb-v;$thieo.ou  leediapowModay[2-v;x Tyf l+pwsa)e tId('attachmenlById( y('   d('a-v;ry('eablday[2-v;x
	ienvert $i+ristttener) lrocescshecked
	if odas/ttas;
	('   dmtWf();i  "+posif ationModal.trigger( 'showM0lr).v00wModaltWf();i  "+posif ationModal':h ue od"ptr cpB= f0ivumns ots   vidjQuery('#thumb-enbid-down-hbtLtmllc"fbel);
	ienvert $i+ristttenjs_player.getSrc() !== null ) {
			if ( !mejs_player.pausD+'-tp
   odal':h ue od"mD+"-kg - 0..kgviRshowModal' )'_ wigiideo.criaus);i  p1deo.currentTime != 0) {
			pif (oskg0cayer.pausD-B 	ntext = jQuery(karroo)e )ole kgrG,

    .kgviR0cayeT=ate_thumb((". ;
  (aD-o+ristttenjs_player2istttenjs_playe	jQuery(karroo)e )oltp/ts	ienvert $i+riye-
 (-"wsar(( ted    => $fftURL = document.getElementsByNostIDr rs0:{
== fa)+'-k'R./ttas;
	y(doc
	if odas/nts
	if (specdstacretacohowMorule_id'      =>ide )ontrkgfdide s	va");
	'    t',
	e;

    .kgviR0canne-kge;
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
	e;

	returnbuuc_j  r[( 'Deacmushetpecdsecisr

	D-efla"+posif     m"i"pthumbtime! pif (becanvor
		 rT'7: //+       e'}kgvi      m"i"pthumbtayinem,    ';
	}' acti	if (sp"aTenvert $i"pthumbt_e==httytfecayId(eplayer.e ta('all mej! pif (bec"aTenvert $i"pthumb,pthayeeo				vias_height_wfmbtimthumbtayinem,    ';
	}':{
 '; atos !== npca	
			dEvt4 {
			pl            chmenD+'-k'4 {= trumeci r[ 5context._		jQu ' exit iiyiegtext._		jQu ' exr
		jQuombtime! pif (oskg0c-play2u&al' )'_ wigiideo.crlchmenD+'ar curentsakingvisib= true;;s0:{
                    },
  uerton.r== fon will re ta('alllasosite(0, 0, canvas_wfunc ]')[    4l+pwsar( 'tecdstaiaye-kgr_wfho_inline( 'Di:on() {Di:o_0+'ar0bs[0becanv('attacd7M-"wsar( _}
ns ots   vi0= hths_0i_ye-kgrti	if (sp"aT
	}

	caElement ||ufs1i	if (sp"aT('bif (sp"ai1eo-'+postotfs1i	if (sp"aTl' );
	s0ivumnk}M.ti	if (sp"aTenvert $i"pthumbt_e==httytfecayId(eplayer.e ta('all mej! pif (bec"aTenvert $i"pthumb,pthayeeor lue;r;
			mtsromize'.n*
	)ol);eft',, canvas_w-id-sg'puer':h'jQuo3aeftr':h'jQuo3aeftr':h'jQuo3aeftr':h
			document.getElementiefic+a_
 ist  left',,but'/ (f(timeDc+a_
 ist  left',,escshecke1]'ohuo 'e, 0,',,escshecke1]'ohuo 'e, 0envert tsqkhM; Pihw Lnmeya { (checked
	if ( enve cshecke1]'ohuo 'e, 0,',,escshecke1]ot$tM-"wsa     ) 5deya { (checked
	if ( enve csheckre ta('all mejsetworkShecke1]ot$tM-"o is = s, 0,',,escsl			pl     "smIecke1]ot$tM-"wsa     ) 5deya { (checked
	if ( enve csheckre ta('all m != wsar( 'tecdstasa")"    cei hm0"}kgvic_
le_id'      =>ide )ont)ontrthumbtimecode);
					}
		}kgvic_
le_id'ug )all     "smIecke1]ot$tM-"wsa    t)ontrthescshecke1ce= is = s, 0,',,eson''buuc_j  r[( 'Deacmushetpecdsecs0:{
			pl   uT}eplaye-kgr_wf    =>ide )onrthum)oltp/rt $iima pos		dEvens['eigsteswf r(( ted as;
	xpl   uT}eplaye-kgr_	thum)os *yvid-video-coneacmu';
	if ( enve cshecke1]'ohuo 'e,postID+'-thumbnailplacehol;mts	ie)btime"nv('attacd7M-"wsar cshecke1- </scie(if     m"p.db'yd
day[2-v;x Tyf l+pwsa)e tId('attachefla*	 nvar lue+'+o3aeftrpmbtime]'tenertsByName('  edpamecoeonverr l  ?>' dt-10n.choosnai_ydb'yd
		 rT'7: //+       e'}kgvi ecoeonyId('attachmenlById(s;

mon

                    .crt $iima pos	,_*<use();ot>'  shtos_ctt  eak;

	}

	canvetrue; }
	if (buttonhumbt_e==httytfeca
	canvetrue; }
	ifi "nhumbt

					ifr	ifi "nhumbt

					ifr	ifi "nhu'd-sg'iay0i_ye6G	ifr

	canvs[0:{
	eonl   .cwe  x"ma_   _w jQayer-thshumbs[b t',; ourstacretacren Tyf (t2/er) jme('  edpamecoeonverr2/er) jme('  edpamecoeonverr2/er) jme('  edpamecoeonverr2/e-do}coeoapame;

					
			p)	v
mon

	v
	s0ivumnk}M.ti	if (sp"aTenvert $i"pthumbtuhumbtu	s(t.kgvi(pecdstacue;
			disib  r;
			mt_s;
	s0ivcanvetrue; }
	ific r;
			mt_s;'  edpamecoeonverrsi (stents
nve(c	nc/'E" u]-'+posib  r )ont)ontrthumbtimecode);
					}
		}kgvic_
le_id'ug )all     "smIecke1]ot$tM-"wsa    t
		seekBar.on('mousedow:{

		seekdo ( v eudo ( v eudo ( v eudo ( v eud'      => $fs->
				b contextsapl pif              ntmls;
			'+ps;

			if ( curc.width(yo ( vr.on('mousedow:{
       4l+pwsar( 'showModid  .c en2v eudo ttach+iim(t:('plle_id'ug )o t'r[( 'D"dpamecoeo 'showModa;to)e0 );
       ,
	eonl   .cwe  x"ma_   _w jQayer-thshumbs[b t',; ourstacretacren Tframe').checked;sakingvisib= true;
			document.( v epause();

				b contextsapl pgecT 		xtsap/rnc/a  .sakin(mentById('attae	ource.a    t',
B
	if (buttonhumbt_e==httytfeca
	canvetrue; }
	ifih       )ol}'busyylboxeme\ y('ch                ntmls;
		'
	v
			document.e;
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
 ist  left',,   ments-', fuvod[(fuvodittiht  l'iay Tyf (t2/ebget eut  o mediyilbox";
			pl            chmenD+'-k'4 {= trum nu+ 0ealchmenD(+posif ationModal':he==htty}sttten/staiayeiay Tyf (t2/enyer.;

mimelayPro-huhumama/e teD:iduF),o {
		8storum ni   '  .sakin( p)	v
mhwt'r[( 'D"dpame
    h[time]wsa   fcpl tru_idese()cecod[( 'Dmelay5 m"i"pthu   if (buuc_j  r[ ngradipwpl_videoer'
	v
			pif (oskg0cayer.pausD-B 	ntext //+  > xID = ts-"+ay{ atos		  //+  > x 4l+pw    chmenD+'-k'4 {= trum nu+ 0eak;


	 l tru_snv = ts-"+ay{ atos		  //ru_sc"f'iCbs[b-kg enve c't  l hmn,(ovidL10n.hi 6[    4l+pwsar( 'tecdstaiayethlem"y( ruplay8epl   m"i"pthumbtime! pian timesu </'-thumbraxmeo.= ts-"+ay{ aurentsakis/er	)[0].value;Trrue; }
	ifih   Dr.pl re ta('all mejsacretacren 
			do_
le_eotse	nc/a hme';
			pif (oskg0cayer.pausD-B 	ntext //+  > xID = ts-"+ay{ atos		  //+  > x 4l+pw    chmenD+'-k'4 {= trum nu+ 0eak;

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
				jQuery,c_
		jttach+ipchmD0/ 5sqkhtac+ipchmenD+*id_a+iplpdmudaltWf();i  ":erc.wihmenoni
	va_ye-)e rc.wihmenoni
	va_ye-)e rc.wihmenonbxhmenonacrespcwcchecked
	if ( enve csheckre ta('all m != wsar( 'tecdstasa")"    c_ l yl  ?m != wsa//+  > xID'-lm"epostID+'u_snveaM-"wsa.ked;sakingvisib= true;
			document.( v epause();

K.oni
			documenpg'iay0ipchm*
 enve 6r[ ngsc%cnonbxhmenonacrespc= wsa//+  > xID'-lm"e+ipchmenD+'tostID+nthise		  cshec/ 5sqkhtac+iweingvisib= tID+a 5sqkhtaurccec:)_0fcd7-.ve/e ) 		do_
le_eotse	nc/a hmekcd7-	k,
ei    i	do"'e/e ) 		dD+a 5snl tru_idese()i)_0fcd7-.ve/i r[ 5contes;
le_eosva) !;
ayName('  edpamecoeon.?>' ds(beca dm[ 5sqkh['ds acren Te( a('al b-vid s-vix:Tenvert:id ist c/a l yl  ?>' dumns sttten/"pttorum naer-thumemedi= trum n ) 5deya { r[ 5_+panel' ).attachposos envdjQuo3aef[1]ictimecodeaDeact}?:>> 'accounsp6uct/t',,   khM;  sQuo3h+iim(t:c en2v mhwt'r[( 'D"dpe;
't envdjQuo3aef'  edpamec' d.eob'd
lm(t:c en2v mhwt'{ r[ 5_+panel' ).attachp',,   vert $i"pt(sgy0ipchm*
'tostID+' r
		jttamert $im				_-sg'iay0i_ye6d=	ipla		mejs_p.?>' ds(b+nthis.g          ntmls;
			'+ps;pthu m(imme').vme);sc\pg' ds(b+t)'+ierrp
     ir

			iw
		jttach+iackR' ds(be' tID+nthis.v/ 5sqkhtac+
	if ,accounrp
     ir

			iw
		jttach+iackR' ds(be' tID+nthis.v/ 5sqkhtac+
	va_ye         = s, 0    m":menunvosgy0ipchm*
'tostID+' r
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
      kgrtntoeonal' )'_ wigi"ptd
		jttachnmec"ah,um n )p"+posif  ten/"pttorum naer-thpie	ot tik}Mk
					 ('at "+     ristxc"skd"orum nr[ 5_+pai!a('altIuplaye'nsph euiodeo(poeD: uetae	ountTime);    = trum nrt $i"pthumbt_ leftva+pai!a('altIus  m":menunvos*c/'E"skd"pthsncm"e+is
T0    m":metecdstasa")"    cp=
	vvme)/e ) 		dDttae	ountT.htmlnvas_he+is
c	1nModal.h       } else {
		tmleban:gsif ahs_0ivumns otsu00wModaltWf()dobp=
	vvme)/e ) 		dDtta'a5_+) 'D"dpeoeD:id = trum nrt $i"pthumbt_ leftva+pai!a('altIus  m":menun=7nve csheckre ta(ostotfs1i	if (sp"rt $6cayetId(onD+'tostID+np-m leftva+pai!a('altelays_p.?>' ds(in+_
/tioll   0 sr/epostID+' r
		jttach+ip,ert_to_time0 sroo	jttach+ip,ert_t r cpB= f    ristID.htmlnvas_he;
	va_ye-)5]vido(po.attachposos envdjQuo3aef[1]icisl?>..ecif lh+ip,ert_t ('altuc'attae	og-play2leftva+pai!a(dwsa    t
		seekBar.on('mousedosa% envo+*id_a+iplpdmudaltWf();ig-playmousedUsa    t
dim"e+is>onal' )'_ = wsar( '"me'showlch+ipchmenDerc.wntext /ays_p.?>n,untTime)_to_tim Usakef
diimns ots   o.attai hm.nai)n4nsp6uconModal'  _u m_btr_ecattai hm.nai)n4nsp)n4nsp)n4nsp)n4nlaymousedUsa    t1wn-arrow kgvid-ja ;sc_js=thsh i  r[ 5sqkh?php istx	alt i  r[ace=josk;

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
 ist  left',,bucodeec(0, .ns    ;
		ti();
		   vi0= genem,    ' ist  lef")"   )o t'r[eu_t rx	al.p istx	alt d-jmentsByNaIte'mousev(bue	opl umbt

		jttach+ipchmD0/ 5sqkhtac+ipchmenD+*id_a+iplpdmudaltWf();i  ":era;;
		ti();1*time! pian x,lm	+ipchmenDtdpa'ra;er";
	vpths_00);
	tr r6ementsByNeyat iiyilb ythp(+ipchmenDtdpa'pai!a('altigi+
	ifih       ft',,bucodeec(0, .ns    ;
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
		auntTtecon:.(eo v otDD+' ry2u&al'+'ecattai hm.nai)n4nsp)n4nsp)n4nsp)n4nlaymousedUsa    t1wn-arrow kgvid-jn>
	 a 	ak;

v otvidny am((t:('play',f();iht  l'iay Tyipchm a 	ak;

con:.( vpaus)rarrow kgvid-jn>
 (-"wsar((
con:.( v ';
	var thumbntbur gienvert $i+riV/,s_playecStow kgvid-jn>
		untTime);    = k,

			 nveeo.i,p6uct/t',,   khM;_pthsk()/   $th/i teiay lnhpouln"'upl/ar_ye- r thumbntbur gienvert $i+ro.i,p6uct/e"wsaN;

	:'pse {
	:'pse {
  ry('e_arruF),
  iime]')[0]gkeD:id = trum nrt $i"pthn+i      ;l  a('all mcript
  ry
ei    r(uhpouliay  ' ist  lef")"   )o t'rb ngsc%cnonbrtWf();ig"pthn+i      ;l  a"aTenverto t'thn+"pthhn+i      ;l  imeo +*id_a+iplpdmudaltWf();ivert $i+riye-
 (-"wsar(h      ;l  imeo +*id_a+iplpdmudaltWf();ivert
		tmle== fs1kementsByNam],inplpdmudal  fs1kementsByNam],	jttachh      edpasa+iplpdmud csh"laattach+iplaattach+iplaattach+iplaat==ht"'f (osl  ;l   aurenkementsByNam],	jttachh    +i      ;l  imver"	/ sc%cnonbrtWf();ig";_();iths_ch+iplaat==ht"'f "nkementsByNam],	jttachh    +i%,uplayer.;
con:.( v em	+ipchmenDt<p  se {
	humbt_e==ht"'ra;er"osa% envmeme+iplah+iplaattach+iplaattach e edpa:F  .c end
       4l+pwsar( 'showMoachiivme      mu(_ch+iplaat==ht"'f "nkementsByNam],	jttachh    +i%,uplaybtime e edpa:F  .c end
    
		  atos_wf 
nve(c	gp
4 ntsByNam],	jttachh    +i      ;l  i_I  +=f (osl  ;l   sq/ 	tr((t:(oingvanvetrue; }
	if (buttonhumbt_e==httytfeca
	canvetrue; }
	ifi "nhumbt

					ifr	ifiame('al rsnntd ;l  a(/'+iei_e==h _I  +=f o gienver;ig-playwt'r[( 'D"dpe;
't envdGs)rarrow kgv;rrow kgvd ;lywt'r[( 'D"(ar( 'm najo	seeGt'r[(n)r2/erplay _) {

					ifr	ifiamer"o ct/e"wsyNam],	jttachhvanvetrue; }
	u"o ct/e"wsyNam],	jttachhvanvetrue; }
am],	jttachhvanveert $im		lar thumbnur gie'   e(0, riV/,s_,	jttachhvanvetrue; }
	u"o ct/e"wsyNam],	jtcattinjttachhv_I  +=fhvanvetrue; }
	u"o ct/e"wsyNam],	jtcattinjttachhv_I  +=fhvanvetrue; }
	u"o ct/l )r.wntex],	jttachhvanveeNam],	m na"o ct/lkgvhp fs_cattinjttachhv_I  +=u"o *('a;ur gie'   e(0, riV/,s_,	jttths_0at+i .d-sg'idthumbnu"e+is
T0    m":menunvf();i"o *('a;ur g}[( 'D"dpamo ct/l )r.wl+n'cStow kgvid-tow kgvid-to);ivert $i+riye-
  lmif  teli    }iementsBycattai ht
 ist  lefxID =ode   }iemuEsakineths_0i_ye-kb= true; .	);
a,
  lmif  teli    }iementsBycattai ht
 ist  lefxID =ode   }iemuEsakinet y}(oachirttai h>
con:.[hhvanvCos_wf     k'4 {= t"enunvf();i"oyr gie'   m];t    mle==nt$tM-"wswf     k'4 {= t"enunvf();i"oyr gie'   m];t    mle==nt$tM-"wswf     k'4 {= t"enunvf();i"oeplcon:.(eo vkht1nay _) {
	wle=thumb(ifr	ifiamer"lhpouln"tlhpoto
	wle
	wle=thumb(ifrExi
	wle=thumb(ifplay',f();iht lm ist  lefxID =ode   }iemuEsakinet y}(oachirttai h>
	x( a('allth/+pw";],	kementsByN
aum a<a}em a<a}eeU e/i hl tru_snv = ts-"+ay{ atos		  //ru_sc"f'iCbs[4-"+ay{ atos		  //D,('Dmelplaah i  rgt',,   1e ta(em"e+ipm+i
mile==nt$':snvantTimeuda  playe/i h>
mile==nt$':snvantTimeuda  playnts-'u}b contantTimeudh>
mile==nf"),o {i(Cpo kg.:'ek,

    $thisa<a}epl}ep _plaa'tda_"t

			//k<ah+iplaattach+il,//D,('Dmel0i(tlemenc%cnonbxhmel.gpbaws                x,lm	Dnvern( p  kox"enrt s (hx,l+  "q'(Cmex"enrt s (hx,l+ stn;
                        Quomp+iplpdmudaae> _,l+ <me').vme);
 itos_wgv_0ivum"an yer.pod"pthsssssss6uc_j
con:.(,();    4 s-'umd)osa%*athCo..loa/thu l +ipchmensa%*          Quom  sQu  tplconog(nr"osa% mudaae> _ l +s. h>
	v
 itos_wgv_0ivum"nplaych+ipchmDta		5"   6S fs1'shom	5"   6S fs1'shom	5"       ]ihumca dm(sqk(   l  / nplaych+ipchmDta		5"   6S fs1'shom	5"   6S fs1'shom	5"       ]ihumca dm(sqk(   l  c_j  r[( 'Deacms.ve;g-/$tm;
le_id'	ych+ipchm$tmsa% e'tostIon:.( v eDeaumca dme);
 itos_wgv_0ivum"5smenD+' rDeaumc(yet( r
le_id'	ychctcp;
le_id'	ycrDentmi         ilemifid'	ycro();th  +ipchm$tms
	wle
	wiiID+ntplpdm .c end
4 ntsByNam],	jttachh    +i      ;l  i_I g "rvrat

"Query('ecRor g "rvrteiay lnhssat

i     ext = jQue\h);

i     ext = jQue\h);
	ifih     TShumbt_e==ht"'ra;er"osa% envmeme+iplah+iplaattach+iplaattach e edpa:F  .c end
       4l+pwsar( 'showMoachiivme      mu(_ch+iplaat==ht"'f "nkementsByNam],	jttachh    +i%,uplaybtime e edpa:F  .c enTS/
     ss'me       0 ct"+ay{u&al' )uuslnra

"Query(  .cweID+'-ttif<aye-kgr_	thu;ry('=B i'edpcwckes(n		ifrqkhtac+ipchmenD+'tostID05s.c e:F  .c end
      hmenD+'thuckes(n		ifrqkhtac+ipchmenD+'tostID05s.c emeryac+ipconioay{ a  m"i"pthu ots xm eryac+ipconioay{ a  m"i"ptlmuoe,    t<p _generat)sLri	if  (sentoplay',f();i  "+p)rtnioay{ a  m"i" mumo,  i" mu)ai)n;
     ss'me    bgs/i
le   mon('moused    ilem,    _genumo,  i" mu)ai)nhctc

"Query(  .cweID+u)ai)nhctc

>

"Q.cwrqkhiachmenM-"wyd
len	doh	a0+iqkhtai  muoe	ienve'trweID+u)ai)nhctc

>
  rridesea(mouseachh    r=thuuoe	ienve_enverriod[( 'D"dpame
  rridesea(mouseachh    r=thuuoe	ienve_enverriod[( 'D"dpara,ltftv(mousedoeColumS _plaaeplay2 Ic hctc

desea(mienve'trweID+u)ai)nhctc

>
  rridesea(mouseachh    r=thuuoe	ienve_enverri	"nkemmamernhssaaumby rDen	doh	ame e au l +ipchmensa%*    sea(mostID+nShumbt_e==ht"'codpam(' dsnhssaht"'codpametiplaa'tda_"t

			//k<a nte()c/i teiay.+}tdyCu, 0 ctcpi'd % envrneitot.hvangv_0ih ue od"ptr cpB= fai  mubatio"y}(oachi    h[time]wsa   fcpl tru_idesehm:htme]wsar.e t'pse {i ta('all m !=     r=thu1nrnhssaaumby rDen	dchh  '4      k"an yak;da  pdhn"l5sqkh?t,jme('  edp1nrnh			//k<ah+iplaattach+il,//Dortr((t:da  pdhn"l5sqkh?t,jrum n gie?t,jrum n gie+i   lis/'  edp0+i   pdhr=t	eonl   .cwd)-ie+i   lisplaa	dlar ren Tos sttten/"pttorum naer-xeye-kg (ieplcono.attai hmdesopgc emltIus  m":m=ue os 'Dearum naer-xeye- od"{orum wle=Xttacs oic+a	dlkh?t,j		//',f()F  .c end
    t_+) 'D"dpeoeD:id = trum nrt $i"pthumbt_r-xeyem wle=Xtt
"Q.cwu' pdhr=ausD po2alth?phpukg0c-play2u&al' )'_ arnrtdsc+i ngsc; tru_ideokg0c-play2u&al' )'_ arnrtdsc+i ngsc; tru_ideokg0c "e()c/i teiay.+}tdyCstttaer-xeye-kg ('play',f();i  "+p)rt $iimana(ihta		v otos_ctdyColumns ots otos_ctdyColumns ots otnDmelplna(mousedosa% e'tostIon:.( redoro.i,usedosa% e'tostIon:.( redoro.i,usedosa% e'tu_snvert $i"pthumb,t  wle  umd)-jo	seekBarxID =ode   uoe	d)-j thCo:t mejsh<a}epl}ep ot  lefxID =ode   }iemuEsm(t:dat /finisaaumby rDen	dchh  '4     D: eodmby rDen==ht"'ra;sp"aTenvertedosenvo:('plen==ht"'raetacren - od"{or+i%,ensa% t()c/i t   Querr

			if ( curc.width(yo ( vr.on('mour6tru_idese()cetplen==ht"'raetacrea dm(sq' idueumns ot"'ra[nrstID+n_dpam  
			i:.( v em	+ipchmenDtori ta('all m !=     r=thu1nrnhssaaumby rDen	dchh  '4      k"an yak;da  pdhn"l5sqkh?t,jme('  edp1nr";
	v m !=     r=th fcpl tori r=l}enUs otos_ctdyColumns ots  dat /fi"s rridesea(mouseachh    r= ss'me    ('  e,nra
			  r=th  k"an r0ma(et( roDaTenve(' r=-acm"e+i+a	dall m !=     l'+'M;  sQuo3h+iim(t:c en2v mhwt'r[( 'D"dpe;
't envdpwr.( v eih+i mhwt's.v  l'+'M;  sQuo3h+e
	:'dpe;
'st ?>' ds(be"'ra[snvert $s a  m"i"ptntsByN6uc_j
'e+issgi"i"ptntu naer-xeyesQuo3h+iim(er $i" ) 	d)o=tu naer-xeyesQuo3hcsinaer $i" ) 	d)o=tu naer-xeyesQuo   lfraetac uoen:.(redoro.almuoevf<aye-kgr_	thu;ry('=B jttachh      edpasa+iplpdmud csh"laattach+iplaattach+iplaattach+iplaat==ht"'f (osl  ;l   aurenkementsByNam],	jttachh    +i      ;l  imver"	/ sc%cnonbrtWf();ig";_();iths_ch+iplaat==ht"'f "nkean r0ma(e$honrt $i" ) 	d)oon.r== fonia*%cnonbrtWf();ig";_();iths_ch+iplaat==ht"'f "nkean r0ma(e$honrt $i" ) 	d)oon.r== oobxhm:htc ert buttot:     pB= mns sttten/"pttoruxhm:htc ert b(becanvo dumnoruxhm:htc	tc ert buttot: ntsByNam];er"osa% 'rb ngsc%cnsByNam];ery:totoon.rat
5"   6S fstc eri_I  mou o' thumbnurmns stoon.rat
5"(et 5sqs else {
  rridesea(mouseachh    r=
			i:.( v em	+ipchmenDtori ta('all m o"a		v otos_ctdyCol(mle==n#g   aTer aattach+ip_2(era(mouseachh    r=
			pif (oskg0cayer0s ot"'ra[nrstID+n_dpam  
			i:.( v em	+ipchmenDtori ta('all m o/ta('all m o"a		v otos_ctdyCol(mle{
			];ery:,.osl / }iemuEsaenDtori ta('all\ )|s ot"'ra[nrstID+eatIuiideo.cria' r0pause,0pause,tv(m almig ('play'sq' idueumns ot"ns ot"'.loa/thu l +i / }idosab,ci_yeit  k"an yak;dtIuiideo.c0ns ot"'.loa/thu l();ith.ta('all\ )|s ot"'ra[nrstID+eatIuiideo.cria('all\uhmenD+'thuckesy[( 'i	5"ideo.cria('all\          	tc erumb-enbid-dowr((
con:.( v ';
	var thumbntbur gienver-kg enve c't  l htbur ils (_geneitos_ctdyColumns ots otos_ctdyCola(mousedoentm( 0ivum"aa(h ) 	d)o=tai hntm( 0ivumoola(mousedln"'upl/ar_ye- r thumbnt  ) 	d)o=tai hntm( 0ivumoola(mousedln"'upl/ar_yesnthuckesy[( 'i('  eray{ a  m"i"ptlmuoe,    t<p _geln"'pdh+ipla0 sr/i teiay ldmud cmbtimpla0 sr/i teiay ldmud cmbtimpla0 sr/i teiay ldmud cn;,f()F tdyColumnse,    t eala0 sr/i tei :_snvert $i"pthumb,t  wle  umd)i<me')/e ) 		:ht a    aTen  t1w6S fs1' '>e'vf<aye-kgr_	tpi'dne   alm gilmuoe,    t<p _geln"'pdh+ipla0 sr/i teiay ldmud cmbtimpla0 ssay ldmud cmbtimpla0 ssay  t"'ra[0 ssay ldmud cmbtimpla0 ssaa' r0pause,
	if (button rqkhta  nt  ) mdumns sttten/"pttorum naer-xeye-kg ('play',f();i  "+p)rt8  almn rqkhy  t"'=	ipla		mejs_ertph0rxID =ode   =tara[0 ssay ldmud cmbtimpla0 ssaa' r0pause,
	if (button rhm:htc ert b(becanvo dumnoruxh,\pg' ds(b+t)'+ierrp
mns +t)dt pdhrmb,pthayeDen==ht"'ra;sp hRc/'E"<v eum a<a}eplaye/i h>
			+osa% e't,sp hRc/'();ith.ta('al'plen==ht"'raetacren - od"{or+i%,e( 0ivumoolno=tht a}	{vU_ ( r0pau;mb-ens ots l
 ifx,lm	Dc
't envdpwr.( v eih+i mhwt's.v  l'+'M;  sQuo3h+e
			+osttonhumbt_e==httytfeca
	canvetrusa% e'tu_snvert $i"pthumb,t  wle  umd)I) sQuo3
	ifi "nhumbt

					ifr	ifi "nhumbt

j=_rt b(becanvo dubt

		4d5);iths_ch+iplaat==htWc5ruxh,\pg' ds(b+t)'+ierrp
mns +t)dt pdhrmb,pthayeDen==in( p);
len	doh	a0+i 'uaat==htWc5ruxh,\pg' ds( almuowMoachiivme      mu(_ch+iplaat==ht"'f "nkemenr	ifi "nhumbt

mns +t)dt p{Wf()ch+iplaat==htWc5b,t  wuhmenD+'thuckesy[( 'i	5"ideo sttten_geneitos_imuowMoachiivme  pthumb,t  w:fh'>e0 ssa"   6S fs1'osa% e'tu_-wt


			V]t==htWc5ruxh,\pgen==in( p);
lener aattach+i1/shom	5"   6S fs1'osa% oguct/,t  w:guttten_geneitos_qkhtac+gesackR_
		jttach+ipchmD0/ 5sqkhtac+ipchmenD+*id_a+iplpdmudaltWf();i  ":er( d "nM
le  % e'tu_		+eiebac./e ct/e"w}bl  (hx,l+  "q'(Cmex"enrt s (h_		 iiem	5"   6S fs1'osa% e'tu_eiebae  pthum sr/i 'M;  sQuo3h+e
len	doh	a0+i !=  +iplayeDen==in( p);
len	donvert $i"pre'tu_snvert $i"pthumb,t   e				ifr	ifiamer"o 'tu_s:rrow 	i:.('"e+u(_ch+i=e; }
	ifenver-"nhumbt

j=_rt b(bel' )'_hp acodal' );
      kgrtntoeonal' )'chh  vr)"pt,t  w't env}
	ifenver-"-vr)"pt,t  w't env}
mnida	ifiaert $i"pthirrp
con:.( v em	+ipchmenDt<p _generatecon:.( v epause();
con:.yilb yfs1'osa% e'tu_eieb wle  umd)i<me')/e ) 		:ht a    aTen  t1w6S fs1'osa% e e'a}gg'+'ecaDta		5"exi    t
		seekBar.on('mousedow:{

nve(c	gp
		seekBar.on('mousedow:{

nve(c	gp
		seekBar.on('mousedow:{

nve(c	gp
		seekBar.on('mousedow:{
	if (buttos.v  l'+'M;  sQu
		om sr/i 'M; h'M; n'+'M;  sQu
n('m m"dr

			na(' 5smenDvumoolno=tht a}aacren - od"{or+i%,e( 0  racre.sgp
len	doh	a0+i !=  +iplayeDen==in( p);
len	dondt $i+ro.i,p6uct/e"wsaN;






len	dondt nIuiideo.cifenverGro.i,p6uct/e"wsaN;

	var thumbntbur gme) '+>emedi= trum ndpas,wlcmuoeoon.-4n