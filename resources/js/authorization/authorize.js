//Import Policy -> dung de xac thuc tren trang Accept.vue
import policies from './policies.js';

export default {
    install (Vue, options){
        Vue.prototype.authorize = function (policy, model){
            if( !window.Auth.signedIn ) 
                return false;
            if(typeof policy === 'string' && typeof model === 'object'){
                const user = window.Auth.user;
        
                return policies[policy](user, model); //policy: modify or accept (user, model)
            }
        } 

        Vue.prototype.signedIn = window.Auth.signedIn; 
    }
}
