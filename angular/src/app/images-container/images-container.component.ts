import { Component, OnInit } from '@angular/core';
import { ImagesService } from '../services/images.service';

@Component({
	selector: 'app-images-container',
	templateUrl: './images-container.component.html',
	styleUrls: ['./images-container.component.scss']
})
export class ImagesContainerComponent implements OnInit {
	imageList = [];
	constructor(
		private imageService: ImagesService
	) {
	
	}

	ngOnInit() {
		this.imageService.getImages();
		this.imageService.getDeletedImages();
	}

	selectCard(card, index) {
		const actionBar = document.querySelector('.cards-action');
		const focusBtn = actionBar.querySelector('a');
		if (focusBtn) {
			focusBtn.focus();
		}

		this.imageService.selectImage(card, index);
	}

	selectCardOnEnter(card, index, event) {
		if (event.keyCode === 13) {
			this.selectCard(card, index);
		}
	}

	getImageList() {
		return this.imageService.visibleImages;
	}

	getActiveImageIndex() {
		return this.imageService.activeImageIndex;
	}
}
