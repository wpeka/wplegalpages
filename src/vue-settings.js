import Vue from 'vue';
import CoreuiVue from '@coreui/vue';
import vSelect from 'vue-select';

import { cilPencil, cilSettings, cilInfo, cibGoogleKeep } from '@coreui/icons';
Vue.use(CoreuiVue);
Vue.component('v-select', vSelect);
import '@coreui/coreui/dist/css/coreui.min.css';
import 'vue-select/dist/vue-select.css';

const j = jQuery.noConflict();

var gen = new Vue({
    el: '#app',
    data(){
        return {
            domain:'',
            generate:null,
            search:null,
            affiliate_disclosure:null,
            is_adult:null,
            privacy:null,
            page_options: obj.page_options,
            privacy_page: '',
            is_footer: obj.lp_options.hasOwnProperty('is_footer') ? obj.lp_options['is_footer'] : null,
            is_banner: obj.lp_options.hasOwnProperty('is_banner') ? obj.lp_options['is_banner'] : null,
            is_cookie: obj.lp_options.hasOwnProperty('is_cookie') ? obj.lp_options['is_cookie'] : null,
            is_age: obj.lp_options.hasOwnProperty('is_age') ? obj.lp_options['is_age'] : null,
            is_popup: obj.lp_options.hasOwnProperty('is_popup') ? obj.lp_options['is_popup'] : null,
        }
    },
    methods: {   
        setValues: function () {
            this.generate =  this.$refs.hasOwnProperty('generate')? this.$refs.generate.checked : null; 
            this.search =  this.$refs.hasOwnProperty('search')? this.$refs.search.checked : null; 
            this.affiliate_disclosure =this.$refs.hasOwnProperty('affiliate_disclosure')? this.$refs.affiliate_disclosure.checked:null;  
            this.is_adult = this.$refs.hasOwnProperty('is_adult')?this.$refs.is_adult.checked:null; 
            this.privacy =this.$refs.hasOwnProperty('privacy')? this.$refs.privacy.checked:null;    
            this.is_footer =this.$refs.hasOwnProperty('footer') && '1' === this.$refs.footer.value ? true :null;    
            this.is_banner =this.$refs.hasOwnProperty('banner') && '1' === this.$refs.banner.value ? true :null;  
            this.is_cookie =this.$refs.hasOwnProperty('cookie') && '1' === this.$refs.cookie.value ? true :null;  
            this.is_age =this.$refs.hasOwnProperty('ageverify') && '1' === this.$refs.ageverify.value ? true :null;  
            this.is_popup =this.$refs.hasOwnProperty('popup') && '1' === this.$refs.popup.value ? true :null;  
            this.privacy_page = this.$refs.hasOwnProperty('privacy_page_mount') && this.$refs.privacy_page_mount.value ? this.$refs.privacy_page_mount.value : '';
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
    },
    mounted(){
        this.setValues();
        j('#testing').css('display','none');
    },
    icons: { cilPencil, cilSettings, cilInfo, cibGoogleKeep }
    
})