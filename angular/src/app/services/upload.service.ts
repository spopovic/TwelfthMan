import { Injectable } from '@angular/core';
import { Http, Response, RequestOptionsArgs, Headers} from '@angular/http';
import { CONFIG } from '../config/config';

@Injectable()
export class UploadService {

	constructor(private http: Http) {
	}

	uploadImage(fileName: string, uploads: File[]) {

		const formData = new FormData();
		formData.append('name', fileName);
		formData.append('image', uploads[0]);

		return this.http.post(CONFIG.IMAGES, formData);
	}

}
