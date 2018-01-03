import { Injectable } from '@angular/core';
import { Http, Response, RequestOptionsArgs, ResponseContentType } from '@angular/http';
import { CONFIG } from '../config/config';
import { Image } from '../models/image';

const images = '/api/image';

@Injectable()
export class ImagesService {
	// All logic is inside this service (to simplify things)
	// Then this service is injected inside components
	// This is simple way to resolve, also there other ways where we can use properties and emit events, but situation is not too complex here
	// Some logic from here could be done in components

	// Handling requests is done here because of logic, but usualy .subscribe is done inside components like is done in form.component

	images: Image[];
	deletedImages: Image[];
	visibleImages = [];
	activeTab = 1;
	imageUrl = null;
	actionBarVisible = false;
	activeImageIndex = -1;
	modalActive = false;

	constructor(private http: Http) {
	}

	getImages() {
		this.http
		.get(CONFIG.IMAGES)
		.subscribe((response: Response) => {
			if (response.ok) {
				this.images = response.json().data;
				this.visibleImages = this.images;
			}
		},
		(error) => {
			// Handle error state...
			console.log(error);
		});
	}

	getDeletedImages() {
		this.http
		.get(CONFIG.DELETED_IMAGES)
		.subscribe((response: Response) => {
			// TODO Handle other types of response
			if (response.ok) {
				this.deletedImages = response.json().data;
			}
		},
		(error) => {
			// Handle error state...
		});
	}

	restoreImage() {
		const index = this.activeImageIndex;
		this.http
		.put(`${CONFIG.IMAGES}/${this.deletedImages[index].id}`, { deleted: false })
		.subscribe((response: Response) => {
			if (response.ok) {
				const item = this.deletedImages.splice(index, 1);
				this.images.push(item[0]);
				this.actionBarVisible = false;
			}
		},
		(error) => {
			// Handle error state...
		});
	}

	selectImage(card, index) {
		this.imageUrl = card.image;
		this.actionBarVisible = true;
		this.activeImageIndex = index;
	}

	deleteImage() {
		const index = this.activeImageIndex;
		this.http
		.put(`${CONFIG.IMAGES}/${this.images[index].id}`, { deleted: true })
		.subscribe((response: Response) => {
			if (response.ok) {
				const item = this.images.splice(index, 1);
				this.deletedImages.push(item[0]);
				this.modalActive = false;
				this.actionBarVisible = false;
				this.activeImageIndex = -1;
			}
		},
		(error) => {
			// Handle error state...
		});

	}

}
