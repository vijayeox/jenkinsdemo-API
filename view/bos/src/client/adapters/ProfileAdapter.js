import { ServiceProvider } from '@osjs/common';
import LocalStorageAdapter from './localStorageAdapter.js';

export class ProfileServiceProvider extends ServiceProvider {

	constructor(core, options = {}) {
		super(core, options || {});
		this.core = core;
		this.lsHelper = new LocalStorageAdapter;
	}

	providers() {
		return [
			'oxzion/profile'
		];
	}

	init() {
		this.core.instance('oxzion/profile', () => ({
			get: () => this.get(),
			set: () => this.set()
		}));
	}
	get() {
        if(this.lsHelper.supported() || lsHelper.cookieEnabled()){
			if(this.lsHelper.get("UserInfo")){
				return this.lsHelper.get("UserInfo");
			} else {
				this.getProfile()
			}
			let profileInfo = this.lsHelper.get("UserInfo");
		}
        return profileInfo['key'];
	}
	set() {
        if(this.lsHelper.supported() || lsHelper.cookieEnabled()){
			if(!this.lsHelper.get("UserInfo")){
				this.getProfile()
			}
		}
	}
	getProfile(){
    	let helper = this.core.make("oxzion/restClient");
		let profileInformation = JSON.parse(helper.profile());
        if(this.lsHelper.supported() || lsHelper.cookieEnabled()){
			this.lsHelper.set("UserInfo",profileInformation["data"]);
		}
	}
	
}