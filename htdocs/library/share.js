(function(w) {
	"use strict";

	w.socialShare = {
        
        vkontakte: function(purl) {
            var url = "https://vk.com/share.php?url=" + encodeURIComponent(purl);
            socialShare.popup(url);
        },
        odnoklassniki: function(purl) {
            var url  = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=' + encodeURIComponent(purl);
            socialShare.popup(url);
        },
        facebook: function(purl) {
            var url = 'http://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(purl);

            socialShare.popup(url);
        },
        twitter: function(purl) {
            var url  = 'https://twitter.com/intent/tweet?';
            url += '&url=' + encodeURIComponent(purl);
            socialShare.popup(url);
        },
        mailru: function(purl) {
            var url  = 'http://connect.mail.ru/share?';
            url += 'url='          + encodeURIComponent(purl);
            socialShare.popup(url);
        },
        popup: function(url) {
            w.open(url,'','toolbar=0,status=0,width=626,height=436');
        },
        getData: function(e) {
            var target = $(e.target);
            var attr = target.attr('data-type') || 'vk',
                curl = w.location.protocol + '//' + w.location.host,
                u   = curl + w.location.pathname + '?events=' + target.parents('.details').data('events-id');

            switch( attr ) {
                case 'vk':
                socialShare.vkontakte(u);
                break;

                case 'fb':
                socialShare.facebook(u);
                break;

                case 'tw':
                socialShare.twitter(u);
                break;

                case 'ok':
                socialShare.odnoklassniki(u);
                break;
            }
        }
    };
})(window);

function share() {
    $(this).click(function(event) {
        socialShare.getData(event);
    });
}