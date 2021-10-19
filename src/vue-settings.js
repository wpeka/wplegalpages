import Vue from 'vue';
import CoreuiVue from '@coreui/vue';
import vSelect from 'vue-select';

import { cilPencil, cilSettings, cilInfo, cibGoogleKeep } from '@coreui/icons';
Vue.use(CoreuiVue);
Vue.component('v-select', vSelect);
import '@coreui/coreui/dist/css/coreui.min.css';
import 'vue-select/dist/vue-select.css';
import {Chrome} from 'vue-color';
import colorpicker from './colorpicker';
import FontPicker from 'font-picker-vue';
import { VueEditor } from "vue2-editor";

Vue.component('chrome', Chrome)
Vue.component('font-picker', FontPicker);
Vue.component('vue-editor', VueEditor);

const j = jQuery.noConflict();

var gen = new Vue({
    el: '#app',
    components: {
        'colorpicker': colorpicker
      },
    data(){
        return {
            apiKey: 'AIzaSyDu1nDK2o4FpxhrIlNXyPNckVW5YP9HRu8',
            domain:'',
            generate:null,
            search:null,
            affiliate_disclosure:null,
            is_adult:null,
            privacy:null,
            page_options: obj.page_options,
            privacy_page: '',
            is_footer: obj.lp_options.hasOwnProperty('is_footer') ? obj.lp_options['is_footer'] : null,
            is_banner: obj.lp_options.hasOwnProperty('is_banner') ? Boolean( parseInt( obj.lp_options.is_banner ) ) : false,
            is_cookie: obj.lp_options.hasOwnProperty('is_cookie') ? obj.lp_options['is_cookie'] : null,
            is_age: obj.lp_options.hasOwnProperty('is_age') ? obj.lp_options['is_age'] : null,
            is_popup: obj.lp_options.hasOwnProperty('is_popup') ? obj.lp_options['is_popup'] : null,
            bar_position_options:['top','bottom'],
            bar_position:'top',
            bar_type_options:['fixed','static'],
            bar_type:'fixed',
            banner_bg_color: obj.lp_banner_options.hasOwnProperty('banner_bg_color') ? obj.lp_banner_options['banner_bg_color']: '#ffffff',
            banner_font: obj.lp_banner_options.hasOwnProperty('banner_font') ? obj.lp_banner_options['banner_font'] : 'Open Sans',
            banner_font_id: obj.lp_banner_options.hasOwnProperty('banner_font_id') ? obj.lp_banner_options['banner_font_id'] : 'Open+Sans',
            banner_text_color: obj.lp_banner_options.hasOwnProperty('banner_text_color') ? obj.lp_banner_options['banner_text_color']: '#000000',
           // banner_font_size: obj.lp_banner_options.hasOwnProperty('banner_font_size') ? obj.lp_banner_options['banner_font_size']: '20px',
            banner_link_color: obj.lp_banner_options.hasOwnProperty('banner_link_color') ? obj.lp_banner_options['banner_link_color']: '#000000',
            banner_number_of_days: Array.from(obj.number_of_days),
            bar_num_of_days:'1',
            banner_custom_css: obj.lp_banner_options.hasOwnProperty('banner_custom_css') ? obj.lp_banner_options['banner_custom_css'] : '',
            customToolbar:[ [{ header: [false, 1, 2, 3, 4, 5, 6] }],
            ["bold", "italic", "underline", "strike"], // toggled buttons
            [
              { align: "" },
              { align: "center" },
              { align: "right" },
              { align: "justify" }
            ],
            ["blockquote", "code-block"],
            [{ list: "ordered" }, { list: "bullet" }, { list: "check" }],
            [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
            [{ color: [] }, { background: [] }], // dropdown with defaults from theme
            ["link", "image", "video"],
            ["clean"] ],
            banner_close_message: obj.lp_banner_options.hasOwnProperty('banner_close_message') ? obj.lp_banner_options['banner_close_message']: 'Close',
            banner_font_size_option: Array.from(obj.font_size_options),
            banner_font_size:'12',
            banner_message: obj.lp_banner_options.hasOwnProperty('banner_message') ? obj.lp_banner_options['banner_message'] : ''
        }
    },
    methods: {   
        setValues: function () {
            this.generate =  this.$refs.hasOwnProperty('generate')? this.$refs.generate.checked : null; 
            this.search =  this.$refs.hasOwnProperty('search')? this.$refs.search.checked : null; 
            this.affiliate_disclosure =this.$refs.hasOwnProperty('affiliate_disclosure')? this.$refs.affiliate_disclosure.checked:null;  
            this.is_adult = this.$refs.hasOwnProperty('is_adult')?this.$refs.is_adult.checked:null; 
            this.privacy =this.$refs.hasOwnProperty('privacy')? this.$refs.privacy.checked:null;       
            // this.is_banner =this.$refs.hasOwnProperty('banner') && '1' === this.$refs.banner.value ? true :null;  
            this.is_cookie =this.$refs.hasOwnProperty('cookie') && '1' === this.$refs.cookie.value ? true :null;  
            this.is_age =this.$refs.hasOwnProperty('ageverify') && '1' === this.$refs.ageverify.value ? true :null;  
            this.is_popup =this.$refs.hasOwnProperty('popup') && '1' === this.$refs.popup.value ? true :null;  
            this.privacy_page = this.$refs.hasOwnProperty('privacy_page_mount') && this.$refs.privacy_page_mount.value ? this.$refs.privacy_page_mount.value : '';
            this.bar_position = this.$refs.hasOwnProperty('bar_position_mount') && this.$refs.bar_position_mount.value ? this.$refs.bar_position_mount.value : '';
            this.bar_type = this.$refs.hasOwnProperty('bar_type_mount') && this.$refs.bar_type_mount.value ? this.$refs.bar_type_mount.value : '';
            this.bar_num_of_days = this.$refs.hasOwnProperty('bar_num_of_days_mount') && this.$refs.bar_num_of_days_mount.value ? this.$refs.bar_num_of_days_mount.value : '';
            this.banner_font_size = this.$refs.hasOwnProperty('banner_font_size_mount') && this.$refs.banner_font_size_mount.value ? this.$refs.banner_font_size_mount.value : '';
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
        onClickBanner() {
            this.is_banner = !this.is_banner;
            this.$refs.banner.value = this.is_banner ? '1' : '0';
        },
        onClickCookie() {
            this.is_cookie = !this.is_cookie;
            this.$refs.cookie.value = this.is_cookie ? '1' : '0';
        },
        onClickAge() {
            this.is_age = !this.is_age;
            this.$refs.ageverify.value = this.is_age ? '1' : '0';
        },
        onClickPopup() {
            this.is_popup = !this.is_popup;
            this.$refs.popup.value = this.is_popup ? '1' : '0';
        },
        onSwitchBanner(){
            this.is_banner = !this.is_banner;
            this.$refs.switch_banner = this.is_banner ? '1' : '0';
        },
        onBannerFont(val) {
            this.banner_font = val.family;
        },
        addBannerContainerID() {
            this.banner_custom_css += `\n.wplegalpages_banner_content{\n\n}\n`;
        },
        addBannerLinksClass(){
            this.banner_custom_css += `\n.wplegalpages_banner_link{\n\n}\n`;
        },
    },
    mounted(){
        this.setValues();
        var that = this;
        j('#testing').css('display','none');
        j('#wplegalpages-banner-form-submit').click(function() {
            var show_banner = that.is_banner;
            var bar_position = that.bar_position;
            var bar_type = that.bar_type;
            var banner_back_color = that.banner_bg_color;
            var banner_font = that.banner_font;
            var banner_font_id = that.banner_font.split(' ').join('+');
            var banner_text_color = that.banner_text_color;
            var banner_font_size = that.banner_font_size;
            var banner_link_color = that.banner_link_color;
            var bar_num_of_days = that.bar_num_of_days;
            var banner_css = j('#wplegalpages-lp-banner-custom-css').text();
            var banner_close_message = that.banner_close_message;
            var banner_message = j('#wplegalpages-lp-banner-message').text();
            console.log(banner_message);
            j.ajax({
                type: 'POST',
                url: obj.ajaxurl,
                data: {
                    'action': 'save_banner_form',
                    'lp-is-banner': show_banner,
                    'lp-bar-position':bar_position,
                    'lp-bar-type':bar_type,
                    'lp-banner-bg-color':banner_back_color,
                    'lp-banner-font':banner_font,
                    'lp-banner-font-id':banner_font_id,
                    'lp-banner-text-color':banner_text_color,
                    'lp-banner-font-size': banner_font_size,
                    'lp-banner-link-color':banner_link_color,
                    'lp-bar-num-of-days':bar_num_of_days,
                    'lp-banner-css': banner_css,
                    'lp-banner-close-message': banner_close_message,
                    'lp-banner-message' : banner_message
                },
                success: function(data) {
                    data = JSON.parse(data);
                    j("#wplegalpages-save-settings-alert").fadeOut(2500);
                }
            })
        })
    },
    icons: { cilPencil, cilSettings, cilInfo, cibGoogleKeep }
    
})