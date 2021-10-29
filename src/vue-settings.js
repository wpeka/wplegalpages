import Vue from 'vue';
import CoreuiVue from '@coreui/vue';
import vSelect from 'vue-select';
import {Chrome} from 'vue-color';
import FontPicker from 'font-picker-vue';
import Draggable from 'vuedraggable';
import ColorPicker from './colorpicker';
import { VueEditor } from "vue2-editor";
import '@coreui/coreui/dist/css/coreui.min.css';
import 'vue-select/dist/vue-select.css';
import vmodal from 'vue-js-modal'
var font_options = require('./google-fonts.json');

import { cilPencil, cilSettings, cilInfo, cibGoogleKeep } from '@coreui/icons';
Vue.use(CoreuiVue);
Vue.use(vmodal, { componentName: 'v-js-modal' } )
Vue.component('vue-editor', VueEditor);
Vue.component('draggable', Draggable);
Vue.component('font-picker', FontPicker);
Vue.component('v-select', vSelect);
Vue.component('chrome', Chrome);
Vue.component('colorpicker', ColorPicker);

const j = jQuery.noConflict();

var gen = new Vue({
    el: '#wplegalpages-settings-app',
    data() {
        return {
            element: "#wplegalpages-settings-app",
            labelIcon: {
                labelOn: '\u2713',
                labelOff: '\u2715'
            },
            apiKey: 'AIzaSyDu1nDK2o4FpxhrIlNXyPNckVW5YP9HRu8',
            customToolbarForm:  [],
            domain:'',
            generate:null,
            search:null,
            affiliate_disclosure:null,
            is_adult:null,
            privacy:null,
            privacy_page: '',
            is_footer: obj.lp_options.hasOwnProperty('is_footer') ? Boolean( parseInt( obj.lp_options.is_footer ) ) : false,
            is_banner: obj.lp_options.hasOwnProperty('is_banner') ? Boolean( parseInt( obj.lp_options['is_banner'] ) ) : false,
            page_options: obj.page_options,
            show_footer_form: false,
            show_banner_form: false,
            footer_legal_pages: [],
            link_bg_color: obj.lp_footer_options.hasOwnProperty('footer_bg_color') ? obj.lp_footer_options['footer_bg_color']: '#ffffff',
            footer_font: obj.lp_footer_options.hasOwnProperty('footer_font') ? obj.lp_footer_options['footer_font'] : 'Open Sans',
            font_size_options: Array.from(obj.font_size_options),
            footer_font_size: '16',
            footer_text_color: obj.lp_footer_options.hasOwnProperty('footer_text_color') ? obj.lp_footer_options['footer_text_color']: '#333333',
            footer_text_align: 'center',
            footer_align_options: ['center','left','right'],
            footer_link_color: obj.lp_footer_options.hasOwnProperty('footer_link_color') ? obj.lp_footer_options['footer_link_color']: '#333333',
            footer_separator: obj.lp_footer_options.hasOwnProperty('footer_separator') ? obj.lp_footer_options['footer_separator'] : '',
            footer_new_tab: obj.lp_footer_options.hasOwnProperty('footer_new_tab') ? Boolean( parseInt( obj.lp_footer_options['footer_new_tab'] ) ) : false,
            footer_custom_css: obj.lp_footer_options.hasOwnProperty('footer_custom_css') ? obj.lp_footer_options['footer_custom_css'] : '',
            font_options: font_options,
        }
    },
    methods: {
        setValues: function () {
            this.generate =  this.$refs.hasOwnProperty('generate')? this.$refs.generate.checked : null; 
            this.search =  this.$refs.hasOwnProperty('search')? this.$refs.search.checked : null; 
            this.affiliate_disclosure =this.$refs.hasOwnProperty('affiliate_disclosure')? this.$refs.affiliate_disclosure.checked:null;  
            this.is_adult = this.$refs.hasOwnProperty('is_adult')?this.$refs.is_adult.checked:null; 
            this.privacy =this.$refs.hasOwnProperty('privacy')? this.$refs.privacy.checked:null; 
            this.privacy_page = this.$refs.hasOwnProperty('privacy_page_mount') && this.$refs.privacy_page_mount.value ? this.$refs.privacy_page_mount.value : '';
            this.is_popup =this.$refs.hasOwnProperty('popup') && '1' === this.$refs.popup.value ? true :null;
            this.privacy_page = this.$refs.hasOwnProperty('privacy_page_mount') && this.$refs.privacy_page_mount.value ? this.$refs.privacy_page_mount.value : '';
            this.footer_legal_pages = this.$refs.footer_legal_pages_mount.value ? this.$refs.footer_legal_pages_mount.value.split(',') : [];
            this.footer_font = this.$refs.footer_font_family_mount.value ? this.$refs.footer_font_family_mount.value : 'Open Sans';
            this.footer_font_size = this.$refs.footer_font_size_mount.value ? this.$refs.footer_font_size_mount.value : '16';
            this.footer_text_align = this.$refs.footer_text_align_mount.value ? this.$refs.footer_text_align_mount.value : 'center';
        },
        onChangeCredit(){
            this.generate= !this.generate;
            this.$refs.generate.value = this.generate ? '1' : '0';
        },
        onChangeSearch(){
            this.search= !this.search;
            this.$refs.search.value = this.search ? '1' : '0';
        },
        onChangeAffiliate(){
            this.affiliate_disclosure= !this.affiliate_disclosure;
            this.$refs.affiliate_disclosure.value = this.affiliate_disclosure ? '1' : '0';
        },
        onChangeIsAdult(){
            this.is_adult= !this.is_adult;
            this.$refs.is_adult.value = this.is_adult ? '1' : '0';
        },
        onChangePrivacy(){
            this.privacy= !this.privacy;
            this.$refs.privacy.value = this.privacy ? '1' : '0';
        },
        onClickFooter() {
            this.is_footer = !this.is_footer;
            this.$refs.footer.value = this.is_footer ? '1' : '0';
        },
        showFooterForm() {
            this.show_footer_form = !this.show_footer_form;

			if ( this.show_footer_form ) {
				this.$modal.show('complainceModal', {
					height: 'auto',
				})
			}
			else {
				this.$modal.hide('complainceModal');
			}
        },
        onSwitchFooter() {
            this.is_footer = !this.is_footer;
            this.$refs.switch_footer = this.is_footer ? '1' : '0';
        },
        onFooterFont(val) {
            this.footer_font = val.family;
        },
        onClickNewTab() {
            this.footer_new_tab = !this.footer_new_tab
            this.$refs.footer_new_tab = this.footer_new_tab ? '1' : '0';
        },
        addContainerID() {
            if( !this.footer_custom_css.includes('#wplegalpages_footer_links_container') ) {
                this.footer_custom_css += `\n#wplegalpages_footer_links_container {\n\n}\n`;
            }
        },
        addLinksClass() {
            if( !this.footer_custom_css.includes('.wplegalpages_footer_link') ) {
                this.footer_custom_css += `\n.wplegalpages_footer_link {\n\n}\n`;}
        },
        addTextClass() {
            if( !this.footer_custom_css.includes('.wplegalpages_footer_separator_text') ) {
                this.footer_custom_css += `\n.wplegalpages_footer_separator_text {\n\n}\n`;
            }
        },
        showBannerForm() {
            this.show_banner_form = !this.show_banner_form;
            // if(this.show_banner_form) {
            //     j('#wplegalpages-announcement-popup-form').css('display', 'flex');
            // }
            // else {
            //     j('#wplegalpages-announcement-popup-form').css('display', 'none')
            // }
        },
        onClickBanner() {
            this.is_banner = !this.is_banner;
            this.$refs.banner.value = this.is_banner ? '1' : '0';
        },

		saveData() {
			console.log('Button clicked!')
            jQuery("#wplegalpages-save-settings-alert").fadeIn(400);
            var show_footer = this.is_footer;
			console.log(this.is_footer);
            var pages = JSON.parse(JSON.stringify(this.footer_legal_pages)).join(',');
            var link_bg_color = j('#wplegalpages-lp-form-bg-color').val();
            var footer_font_family = this.footer_font;
            var footer_font_family_id = this.footer_font.split(' ').join('+');
            var footer_fontsize = this.footer_font_size;
            var text_color = j('#wplegalpages-lp-form-text-color').val();
            var footer_align = this.footer_text_align;
            var link_color = j('#wplegalpages-lp-form-link-color').val();
            var separator = j('#wplegalpages-lp-form-separator').val();
            var footer_new_tab = this.footer_new_tab;
            var footer_css = j('#wplegalpages-lp-footer-custom-css').text();
            j.ajax({
                type: 'POST',
                url: obj.ajaxurl,
                data: {
                    'action': 'lp_save_footer_form',
                    'lp-footer-pages': pages,
                    'lp-is-footer': show_footer,
                    'lp-footer-link-bg-color': link_bg_color,
                    'lp-footer-font': footer_font_family,
                    'lp-footer-font-family-id': footer_font_family_id,
                    'lp-footer-font-size': footer_fontsize,
                    'lp-footer-text-color': text_color,
                    'lp-footer-align': footer_align,
                    'lp-footer-link-color': link_color,
                    'lp-footer-separator': separator,
                    'lp-footer-new-tab': footer_new_tab,
                    'lp-footer-css': footer_css,
                    'lp_footer_nonce_data': j('#wplegalpages-footer-form-nonce').val(),
                },
                success: function(data) {
					j("#wplegalpages-save-settings-alert").fadeOut(2500);
                }    
            })

			this.showFooterForm();
		}
    },
    mounted() {
        this.setValues();
        var that = this;
        j('#testing').css('display','none');
        j('.wplegalpages-save-button').click(function() {
            jQuery("#wplegalpages-save-settings-alert").fadeIn(400);
            var dataV = jQuery("#lp-save-settings-form").serialize();
            jQuery.ajax({
                type: 'POST',
                url: obj.ajaxurl,
                data: dataV + '&action=lp_save_admin_settings', 
            }).done(function (data) {
                j("#wplegalpages-save-settings-alert").fadeOut(2500);
            });
        })
    },
    icons: { cilPencil, cilSettings, cilInfo, cibGoogleKeep }
})