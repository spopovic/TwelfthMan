import { Injectable } from '@angular/core';
import { Http, Response, ResponseContentType, RequestOptionsArgs  } from '@angular/http';
import { saveAs } from 'file-saver';

@Injectable()
export class DownloadService {

	cachedUrl: string;
	data: Blob;

	constructor(private http: Http) {
	}

	getImage(url) {
		if (this.cachedUrl && this.cachedUrl === url) {
			saveAs(this.data);
		} else {
			this.downloadImage(url);
		}
	}

	downloadImage(url) {
		const requestOptions: RequestOptionsArgs = { responseType: ResponseContentType.Blob };
		this.http
		.get(url, requestOptions)
		.subscribe((response: Response) => {
			if (response.ok) {
				this.cachedUrl = url;
				this.data = new Blob([response.json()], { type: response.json().type } );
				saveAs(this.data);
			}
		},
		(error) => {
			// Error happened during the retreiving download
			// No download, clean cached value
			this.cachedUrl = '';
			this.data = undefined;
		});
	}

}
