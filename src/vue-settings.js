import Vue from 'vue';
import CoreuiVue from '@coreui/vue';
import vSelect from 'vue-select';
import {Chrome} from 'vue-color';
import FontPicker from 'font-picker-vue';
import Draggable from 'vuedraggable';
import ColorPicker from './vue-components/colorpicker';
import Tooltip from './vue-components/tooltip';
import { VueEditor } from "vue2-editor";
import '@coreui/coreui/dist/css/coreui.min.css';
import 'vue-select/dist/vue-select.css';
import VueModal from '@kouts/vue-modal'
import '@kouts/vue-modal/dist/vue-modal.css';
var font_options = require('./google-fonts.json');

import { cilPencil, cilSettings, cilInfo, cibGoogleKeep } from '@coreui/icons';
Vue.use(CoreuiVue);
Vue.component('v-modal', VueModal);
Vue.component('vue-editor', VueEditor);
Vue.component('draggable', Draggable);
Vue.component('font-picker', FontPicker);
Vue.component('v-select', vSelect);
Vue.component('chrome', Chrome);
Vue.component('colorpicker', ColorPicker);
Vue.component('tooltip', Tooltip);

const j = jQuery.noConflict();

var gen = new Vue({
    el: '#wplegalpages-settings-app',
    data() {
        return {
          element: "#wplegalpages-settings-app",
          labelIcon: {
            labelOn: "\u2713",
            labelOff: "\u2715",
          },
          searchLabel: "Show Legal Pages in Search",
          configure_image_url: require("../admin/images/configure_icon.svg"),
          configure_alt_text: "WP LegalPages configure icon",
          appendField: ".wplegalpages-settings-container",
          customToolbarForm: [],
          domain: "",
          generate: null,
          search: null,
          affiliate_disclosure: null,
          enable_block: null,
          is_adult: null,
          privacy: null,
          privacy_page: "",
          analytics_on:
            true === obj.ask_for_usage_optin || "1" === obj.ask_for_usage_optin
              ? true
              : false,
          is_footer: obj.lp_options.hasOwnProperty("is_footer")
            ? Boolean(parseInt(obj.lp_options.is_footer))
            : false,
          is_banner: obj.lp_options.hasOwnProperty("is_banner")
            ? Boolean(parseInt(obj.lp_options["is_banner"]))
            : false,
          is_age: obj.age_verify_enable ? obj.age_verify_enable : "content",
          age_button_content: this.is_age === "site" ? true : false,
          is_popup: obj.popup_enabled
            ? Boolean(parseInt(obj.popup_enabled))
            : false,
          page_options: obj.page_options,
          show_footer_form: false,
          show_banner_form: false,
          show_age_verification_form: false,
          show_popup_form: false,
          footer_pages: [],
          footer_pages_drag: [],
          footer_legal_pages: obj.lp_footer_options.hasOwnProperty(
            "footer_legal_pages"
          )
            ? obj.lp_footer_options["footer_legal_pages"]
            : [],
          link_bg_color: obj.lp_footer_options.hasOwnProperty("footer_bg_color")
            ? obj.lp_footer_options["footer_bg_color"]
            : "#ffffff",
          footer_font: obj.lp_footer_options.hasOwnProperty("footer_font")
            ? obj.lp_footer_options["footer_font"]
            : "Open Sans",
          font_size_options: Array.from(obj.font_size_options),
          footer_font_size: "16",
          footer_text_color: obj.lp_footer_options.hasOwnProperty(
            "footer_text_color"
          )
            ? obj.lp_footer_options["footer_text_color"]
            : "#333333",
          footer_text_align: "center",
          footer_align_options: ["center", "left", "right"],
          footer_link_color: obj.lp_footer_options.hasOwnProperty(
            "footer_link_color"
          )
            ? obj.lp_footer_options["footer_link_color"]
            : "#333333",
          footer_separator: obj.lp_footer_options.hasOwnProperty(
            "footer_separator"
          )
            ? obj.lp_footer_options["footer_separator"]
            : "",
          footer_new_tab: obj.lp_footer_options.hasOwnProperty("footer_new_tab")
            ? Boolean(parseInt(obj.lp_footer_options["footer_new_tab"]))
            : false,
          footer_custom_css: obj.lp_footer_options.hasOwnProperty(
            "footer_custom_css"
          )
            ? obj.lp_footer_options["footer_custom_css"]
            : "",
          font_options: font_options,
          bar_position_options: ["top", "bottom"],
          bar_position: "top",
          bar_type_options: ["fixed", "static"],
          bar_type: "fixed",
          banner_bg_color: obj.lp_banner_options.hasOwnProperty(
            "banner_bg_color"
          )
            ? obj.lp_banner_options["banner_bg_color"]
            : "#ffffff",
          banner_font: obj.lp_banner_options.hasOwnProperty("banner_font")
            ? obj.lp_banner_options["banner_font"]
            : "Open Sans",
          banner_font_id: obj.lp_banner_options.hasOwnProperty("banner_font_id")
            ? obj.lp_banner_options["banner_font_id"]
            : "Open+Sans",
          banner_text_color: obj.lp_banner_options.hasOwnProperty(
            "banner_text_color"
          )
            ? obj.lp_banner_options["banner_text_color"]
            : "#000000",
          banner_link_color: obj.lp_banner_options.hasOwnProperty(
            "banner_link_color"
          )
            ? obj.lp_banner_options["banner_link_color"]
            : "#000000",
          banner_number_of_days: Array.from(obj.number_of_days),
          bar_num_of_days: "1",
          banner_custom_css: obj.lp_banner_options.hasOwnProperty(
            "banner_custom_css"
          )
            ? obj.lp_banner_options["banner_custom_css"]
            : "",
          banner_close_message: obj.lp_banner_options.hasOwnProperty(
            "banner_close_message"
          )
            ? obj.lp_banner_options["banner_close_message"]
            : "Close",
          banner_font_size_option: Array.from(obj.font_size_options),
          banner_font_size: "16",
          banner_message: obj.lp_banner_options.hasOwnProperty("banner_message")
            ? obj.lp_banner_options["banner_message"]
            : "Our [wplegalpages_page_link] have been updated on [wplegalpages_last_updated].",
          banner_multiple_message: obj.lp_banner_options.hasOwnProperty(
            "banner_multiple_message"
          )
            ? obj.lp_banner_options["banner_multiple_message"]
            : "Our [wplegalpages_page_link] pages have recently been updated.",
          age_verify_for: "Guests only",
          age_verify_for_options: ["Guests only", "All visitors"],
          minimum_age: obj.minimum_age ? obj.minimum_age : 18,
          age_type_options: [
            "Input Date of Birth",
            "Yes/No Buttons",
            "Yes/Leave Buttons",
          ],
          redirect_url: obj.redirect_url ? obj.redirect_url : "",
          is_valid_url: true,
          age_type_option: "Yes/No Buttons",
          yes_leave: true,
          age_buttons: true,
          age_yes_button: obj.age_yes_button ? obj.age_yes_button : "Yes, I am",
          age_no_button: obj.age_no_button ? obj.age_no_button : "No, I am not",
          age_description: obj.age_description
            ? obj.age_description
            : `To proceed, we need to verify that you're {age} or older.\n<span>Please verify your age.</span>\n{form}`,
          invalid_age_description: obj.invalid_age_description
            ? obj.invalid_age_description
            : `We are Sorry. You are not of valid age.`,
          age_verify_popup: 1,
          cookie_text_size_options: Array.from(obj.cookie_text_size_options),
          create_popup_clicked: false,
        };
    },
    methods: {
        setValues: function () {
            this.generate =  this.$refs.hasOwnProperty('generate')? this.$refs.generate.checked : null;
            this.search =  this.$refs.hasOwnProperty('search')? this.$refs.search.checked : null;
            this.affiliate_disclosure =this.$refs.hasOwnProperty('affiliate_disclosure')? this.$refs.affiliate_disclosure.checked:null;
            this.enable_block =this.$refs.hasOwnProperty('enable_block')? this.$refs.enable_block.checked:null;
            this.is_adult = this.$refs.hasOwnProperty('is_adult')?this.$refs.is_adult.checked:null;
            this.privacy =this.$refs.hasOwnProperty('privacy')? this.$refs.privacy.checked:null;
            this.privacy_page = this.$refs.hasOwnProperty('privacy_page_mount') && this.$refs.privacy_page_mount.value ? this.$refs.privacy_page_mount.value : '';
            this.privacy_page = this.$refs.hasOwnProperty('privacy_page_mount') && this.$refs.privacy_page_mount.value ? this.$refs.privacy_page_mount.value : '';
            this.footer_font = this.$refs.footer_font_family_mount.value ? this.$refs.footer_font_family_mount.value : 'Open Sans';
            this.footer_font_size = this.$refs.footer_font_size_mount.value ? this.$refs.footer_font_size_mount.value : '16';
            this.footer_text_align = this.$refs.footer_text_align_mount.value ? this.$refs.footer_text_align_mount.value : 'center';
            this.bar_position = this.$refs.hasOwnProperty('bar_position_mount') && this.$refs.bar_position_mount.value ? this.$refs.bar_position_mount.value : '';
            this.bar_type = this.$refs.hasOwnProperty('bar_type_mount') && this.$refs.bar_type_mount.value ? this.$refs.bar_type_mount.value : '';
            this.footer_font = this.$refs.banner_font_family_mount.value ? this.$refs.banner_font_family_mount.value : 'Open Sans';
            this.bar_num_of_days = this.$refs.hasOwnProperty('bar_num_of_days_mount') && this.$refs.bar_num_of_days_mount.value ? this.$refs.bar_num_of_days_mount.value : '';
            this.banner_font_size = this.$refs.hasOwnProperty('banner_font_size_mount') && this.$refs.banner_font_size_mount.value ? this.$refs.banner_font_size_mount.value : '';
            this.age_verify_for = this.$refs.hasOwnProperty('age_verify_for_mount') ? this.$refs.age_verify_for_mount.value : 'Guests only';
            this.age_type_option = this.$refs.hasOwnProperty('age_type_option_mount') ? this.$refs.age_type_option_mount.value : 'Yes/No Buttons';
            this.age_button_content = this.is_age === 'site' ? true : false;
            this.age_buttons = this.age_type_option === 'Yes/No Buttons' ? true : false;
            this.yes_leave = this.age_type_option === 'Yes/Leave Buttons' ? true : false;
            
            let navLinks = j('.nav-link').map(function () {
                return this.getAttribute('href');
            });
            for(let i=0; i<this.page_options.length; i++) {
                if( this.footer_legal_pages.includes(this.page_options[i].code.toString()) ) {
                    this.footer_pages.push(this.page_options[i]);
                    this.footer_pages_drag.push(this.page_options[i]);
                }
            }
            for (let i = 0; i < navLinks.length; i++) {
                let re = new RegExp(navLinks[i]);
                if (window.location.href.match(re)) {
                    this.$refs.active_tab.activeTabIndex = i;
                    break;
                }
            }
        },
        onChangeAskForUsage() {
            this.analytics_on = !this.analytics_on;
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
        onChangeEnableBlock(){
            this.enable_block= !this.enable_block;
            this.$refs.enable_block.value = this.enable_block ? '1' : '0';
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
        },
        onSwitchFooter() {
            this.is_footer = !this.is_footer;
            this.$refs.switch_footer = this.is_footer ? '1' : '0';
        },
        onFooterPagesSelect(value){
            let temp_array = [];
            this.footer_pages_drag = [];
            for(let i=0; i<value.length; i++) {
                temp_array[i] = value[i];
                for(let j=0;j<this.page_options.length;j++) {
                    if( this.page_options[j].code === value[i]) {
                        this.footer_pages_drag[i] = this.page_options[j];
                        break;
                    }
                }
            }
            this.footer_legal_pages = temp_array;
        },
        onDragPages() {
            let footer_pages_length = this.footer_pages_drag.length;
            let temp_array = [];
            for(let i=0; i<footer_pages_length; i++) {
                temp_array[i] = this.footer_pages_drag[i].code;
            }
            this.footer_legal_pages = temp_array;
            this.footer_pages = temp_array;
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
                this.footer_custom_css += `#wplegalpages_footer_links_container {\n\n}\n`;
            }
        },
        addLinksClass() {
            if( !this.footer_custom_css.includes('.wplegalpages_footer_link') ) {
                this.footer_custom_css += `.wplegalpages_footer_link {\n\n}\n`;}
        },
        addTextClass() {
            if( !this.footer_custom_css.includes('.wplegalpages_footer_separator_text') ) {
                this.footer_custom_css += `.wplegalpages_footer_separator_text {\n\n}\n`;
            }
        },
        showBannerForm() {
            this.show_banner_form = !this.show_banner_form;
        },
        onClickBanner() {
            this.is_banner = !this.is_banner;
            this.$refs.banner.value = this.is_banner ? '1' : '0';
        },
        onSwitchBanner(){
            this.is_banner = !this.is_banner;
            this.$refs.switch_banner = this.is_banner ? '1' : '0';
        },
        addBannerContainerID() {
            if(!this.banner_custom_css.includes('.wplegalpages_banner_content')) {
                this.banner_custom_css += `.wplegalpages_banner_content{\n\n}\n`;
            }
        },
        addBannerLinksClass(){
            if(!this.banner_custom_css.includes('.wplegalpages_banner_link')) {
                this.banner_custom_css += `.wplegalpages_banner_link{\n\n}\n`;
            }
        },
        addBannerPageCode() {
            if(!this.banner_message.includes('[wplegalpages_page_title]')) {
                this.banner_message = this.banner_message.slice(0, this.banner_message.length)
                this.banner_message +=  '[wplegalpages_page_title]';
            }
        },
        addBannerPageLinkTitle() {
            if(!this.banner_message.includes('[wplegalpages_page_link]')) {
                this.banner_message = this.banner_message.slice(0, this.banner_message.length)
                this.banner_message += '[wplegalpages_page_link]';
            }
        },
        addBannerPageHref() {
            if(!this.banner_message.includes('[wplegalpages_page_href]')){
                this.banner_message = this.banner_message.slice(0, this.banner_message.length)
                this.banner_message += '[wplegalpages_page_href]';
            }
        },
        addBannerPageLed() {
            if(!this.banner_message.includes('[wplegalpages_last_updated]')) {
                this.banner_message += '[wplegalpages_last_updated]';
            }
        },
        addBannerDefaultMsg() {
            this.banner_message = 'Our [wplegalpages_page_link] have been updated on [wplegalpages_last_updated].';
        },
        addBannerMultiplePageCode() {
            if(!this.banner_multiple_message.includes('[wplegalpages_page_title]')) {
                this.banner_multiple_message +=  '[wplegalpages_page_title]';
            }
        },
        addBannerMultiplePageLinkTitle() {
            if(!this.banner_multiple_message.includes('[wplegalpages_page_link]')) {
                this.banner_multiple_message += '[wplegalpages_page_link]';
            }
        },
        addBannerMultipleDefaultMsg() {
            this.banner_multiple_message = 'Our [wplegalpages_page_link] pages have recently been updated.';
        },
        addBannerMultiplePageLed() {
            if(!this.banner_multiple_message.includes('[wplegalpages_last_updated]')) {
                this.banner_multiple_message += '[wplegalpages_last_updated]';
            }
        },
        showAgeVerificationForm() {
            this.show_age_verification_form = !this.show_age_verification_form;
        },
        onClickAge() {
            this.is_age = this.is_age === 'content' ? 'site' : 'content';
            this.$refs.ageverify = this.is_age;
            this.age_button_content = this.is_age === 'content' ? false : true;
        },
        onSwitchAge() {
            this.is_age = this.is_age === 'content' ? 'site' : 'content';
            this.$refs.switch_age = this.is_age;
            this.age_button_content = this.is_age === 'content' ? false : true;
        },
        showButtonOptions() {
            this.age_buttons = this.age_type_option === 'Yes/No Buttons' ? true : false;
            this.yes_leave = this.age_type_option === 'Yes/Leave Buttons' ? true : false;
            if(this.age_type_option === 'Input Date of Birth')
                this.age_verify_popup = 0;
            else if(this.age_type_option === 'Yes/No Buttons')
                this.age_verify_popup = 1;
            else
                this.age_verify_popup = 2;
        },
        showPopupForm() {
            this.show_popup_form = !this.show_popup_form;
        },
        onClickPopup() {
            this.is_popup = !this.is_popup;
            this.$refs.popup= this.is_popup ? '1' : '0';
            // Display/Hide the 'Create Popup' submenu according to the toggle button in modal of 'Create Popus' card of 'Compliances Tab'
            if( this.is_popup ) {
                jQuery('.wplegalpages-popup-submenu').css('display', 'block')
            }
            else {
                jQuery('.wplegalpages-popup-submenu').css('display', 'none')
            }
			this.create_popup_clicked = true;
        },
        onSwitchPopup(){
            this.is_popup = !this.is_popup;
            this.$refs.switch_popup = this.is_popup ? '1' : '0';
            // Display/Hide the 'Create Popup' submenu according to the toggle button in modal of 'Create Popus' card of 'Compliances Tab'
            if( this.is_popup ) {
                jQuery('.wplegalpages-popup-submenu').css('display', 'block')
            }
            else {
                jQuery('.wplegalpages-popup-submenu').css('display', 'none')
            }
			this.create_popup_clicked = true;
        },
        
		saveFooterData() {
            jQuery("#wplegalpages-save-settings-alert").fadeIn(400);
            var show_footer = this.is_footer;
            var pages = '';
            if( this.footer_legal_pages !== '' ) {
                pages = JSON.parse(JSON.stringify(this.footer_legal_pages)).join(',');
            }
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
		},
        saveBannerData() {
            jQuery("#wplegalpages-save-settings-alert").fadeIn(400);
            var show_banner = this.is_banner;
            var bar_pos = this.bar_position;
            var type_bar = this.bar_type;
            var banner_back_color = j('#wplegalpages-lp-banner-bg-color').val();
            var banner_fnt = this.banner_font;
            var banner_font_id = this.banner_font.split(' ').join('+');
            var banner_text_color = j('#wplegalpages-lp-banner-text-color').val();
            var banner_font_size = this.banner_font_size;
            var banner_link_color = j('#wplegalpages-lp-banner-link-color').val();
            var bar_days = this.bar_num_of_days;
            var banner_css = j('#wplegalpages-lp-banner-custom-css').text();
            var banner_close_message = this.banner_close_message;
            var banner_msg = j('#wplegalpages-lp-banner-message').text();
            var banner_multiple_msg = j('#wplegalpages-lp-banner-multiple-message').text();
            j.ajax({
                type: 'POST',
                url: obj.ajaxurl,
                data: {
                'action': 'save_banner_form',
                'lp-is-banner': show_banner,
                'lp-bar-position':bar_pos,
                'lp-bar-type':type_bar,
                'lp-banner-bg-color':banner_back_color,
                'lp-banner-font':banner_fnt,
                'lp-banner-font-id':banner_font_id,
                'lp-banner-text-color':banner_text_color,
                'lp-banner-font-size': banner_font_size,
                'lp-banner-link-color':banner_link_color,
                'lp-bar-num-of-days':bar_days,
                'lp-banner-css': banner_css,
                'lp-banner-close-message': banner_close_message,
                'lp-banner-message' : banner_msg,
                'lp-banner-multiple-msg': banner_multiple_msg,
                'lp_banner_nonce_data': j('#wplegalpages-banner-form-nonce').val(),
            },
            success: function(data) {
                j("#wplegalpages-save-settings-alert").fadeOut(2500);
            }
            })
            this.showBannerForm();
        },
        saveAgeData() {
            jQuery("#wplegalpages-save-settings-alert").fadeIn(400);
            var is_age_verify = this.is_age;
            var verify_age_for = this.age_verify_for;
            var min_age = this.minimum_age;
            var display_option = this.age_type_option;
            var yes_button_text = this.age_yes_button;
            var no_button_text = this.age_no_button;
            var redirect_url_text = this.redirect_url;
            var age_popup_no = this.age_verify_popup;
            j.ajax({
                type: 'POST',
                url: obj.ajaxurl,
                data: {
                'action': 'save_age_form',
                'lp-is-age': is_age_verify,
                'lp-verify-for': verify_age_for,
                'lp-minimum-age': min_age,
                'lp_age_nonce_data': j('#wplegalpages-age-form-nonce').val(),
                'lp-display-option': display_option,
                'lp-yes-button-text': yes_button_text,
                'lp-no-button-text': no_button_text,
                'lp-redirect-url': redirect_url_text,
                'lp-age-popup-no': age_popup_no, 
                'lp-verification-description': j('#wplegalpages-lp-age-description-message').text(),
                'lp-verification-description-invalid': j('#wplegalpages-lp-age-description-invalid-message').text(),
            },
            success: function(data) {
                j("#wplegalpages-save-settings-alert").fadeOut(2500);
            }
            })
            this.showAgeVerificationForm();
        },


        savePopupData() {
            jQuery("#wplegalpages-save-settings-alert").fadeIn(400);
            var show_popup = this.is_popup;
            j.ajax({
                type: 'POST',
                url: obj.ajaxurl,
                data: {
                'action': 'save_popup_form',
                'lp-is-popup': show_popup,
                'lp_popup_nonce_data': j('#wplegalpages-popup-form-nonce').val(),
            },
            success: function(data) {
                j("#wplegalpages-save-settings-alert").fadeOut(2500);
            }
            })
            this.showPopupForm();
        },
        saveGeneralSettings() {
			var that = this;
	        const form = document.querySelector("#lp-save-settings-form");
                
	        // Check if form is valid
	        if (!form.checkValidity()) {
	        	form.reportValidity(); // Show validation errors in browser
	        	return;
	        }
        
	        jQuery("#wplegalpages-save-settings-alert").fadeIn(400);
	        var dataV = jQuery(form).serialize();
	        jQuery.ajax({
	        	type: 'POST',
	        	url: obj.ajaxurl,
	        	data: dataV + '&action=lp_save_admin_settings',
	        }).done(function (data) {
	        	j("#wplegalpages-save-settings-alert").fadeOut(2500);
	        	if (that.create_popup_clicked) {
	        		that.create_popup_clicked = false;
	        		location.reload();
	        	}
	        });
        }
    },
    mounted() {
        this.setValues();
        j('#testing').css('display','none');
        j('.wplegalpages-settings-nav .nav .nav-item .nav-link').on('click', function() {
            let adminbar_height = j('#wpadminbar').height();
            let nav_bar_distance =  j('.wplegalpages-settings-nav').offset().top;
            let scrolled_distance = nav_bar_distance - j(window).scrollTop();
            if( scrolled_distance <= adminbar_height ) {
                window.scroll(0, nav_bar_distance-adminbar_height);
            }
        })
    },
    icons: { cilPencil, cilSettings, cilInfo, cibGoogleKeep }
})
var pop = new Vue({
  el: ".wplegalpages-popup-app",
  data() {
    return {
      popupVisible: false,
      successMessage: "",
      formData: {
        id: null,
        title: "",
        content: "",
      },
    };
  },
  methods: {
    closePopup() {
      this.popupVisible = false;
    },
    editPopup(id) {
      this.popupVisible = true;
      this.editMode = true;
      this.formData = {
        id: id,
        title: "",
        content: "",
        legalpage_id: "",
      };

      jQuery.ajax({
        type: "POST",
        url: obj.ajaxurl, // or obj.ajaxurl if you're using a localized object
        data: {
          action: "wplegalpages_load_edit_form_data",
          page_id: id,
        },
        success: (data) => {
          if (data.success) {
            this.formData.title = data.data.title;
            this.formData.content = data.data.content;
            this.formData.legalpage_id = data.data.legalpage_id;

            // Inject the content into TinyMCE editor
            setTimeout(() => {
              if (typeof tinyMCE !== "undefined" && tinyMCE.get("content")) {
                tinyMCE.get("content").setContent(data.data.content);
              } else {
                // Fallback if TinyMCE is not initialized yet
                document.getElementById("content").value = data.data.content;
              }
            }, 100);
            

          } else {
            consoloe.log(
              "Error: " + (data.data || "Unable to load popup data")
            );
          }
        },
        error: (xhr, status, error) => {
          console.error("AJAX Error:", status, error);
        },
      });
    },
    savePopupData() {
      // Sync TinyMCE → hidden textarea → Vue
      if (typeof tinyMCE !== "undefined") {
        tinyMCE.triggerSave();
      }
      var legalpage_id = document.getElementById("wplp")?.value || "";
      var content = document.getElementById("content")?.value || "";
      var title = document.getElementById("lp-name")?.value || "";

      if (!title || !content) {
        return;
      }
      // Fallback for ID if missing
      const formId = this.formData.id != null ? this.formData.id : 0;
      //  Send AJAX
      jQuery.ajax({
        type: "POST",
        url: obj.ajaxurl,
        data: {
          action: "wplegalpages_update_popup",
          id: formId, // or send real ID if editing
          title: title,
          content: content,
          legalpage_id: legalpage_id,
        },
        success: function (response) {
          if (response.success) {
           localStorage.setItem("popup_saved", "1");
           window.location.reload();
          } else {
            console.log("Save failed: " + response.data);
          }
        },
        error: function (xhr, status, error) {
          console.log("AJAX error: " + error);
        },
      });
    },
  },
  mounted() {
     if (localStorage.getItem("popup_saved") === "1") {
       this.successMessage = "Popup saved successfully!";

       // Clear it so it doesn't show on every reload
       localStorage.removeItem("popup_saved");

       // Hide after 3 seconds
       setTimeout(() => {
         this.successMessage = "";
       }, 8000);
     }
  },
});