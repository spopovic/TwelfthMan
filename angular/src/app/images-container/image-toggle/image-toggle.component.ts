import { Component, OnInit } from '@angular/core';
import { ImagesService } from '../../services/images.service';

@Component({
	selector: 'app-image-toggle',
	templateUrl: './image-toggle.component.html',
	styleUrls: ['./image-toggle.component.scss']
})
export class ImageToggleComponent implements OnInit {
	activeText = 'Active';
	deleteText = 'Deleted';

	constructor(
		private imageService: ImagesService
	) { }

	ngOnInit() {
	}

	changeVisibleImages(images, tabIndex) {
		this.imageService.visibleImages = images;
		this.imageService.activeTab = tabIndex;
		this.imageService.actionBarVisible = false;
		this.imageService.activeImageIndex = -1;
	}

	getActiveImages() {
		return this.imageService.images;
	}

	getDeletedImages() {
		return this.imageService.deletedImages;
	}
}
