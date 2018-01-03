import { Component, OnInit } from '@angular/core';
import { DownloadService } from '../../services/download.service';
import { ImagesService } from '../../services/images.service';

@Component({
	selector: 'app-image-action',
	templateUrl: './image-action.component.html',
	styleUrls: ['./image-action.component.scss']
})
export class ImageActionComponent implements OnInit {

	constructor(
		private imageService: ImagesService,
		private downloadService: DownloadService
	) {}

	ngOnInit() {
	}

	restoreCard() {
		this.imageService.restoreImage();
	}

	download(url) {
		return this.downloadService.getImage(url);
	}

	callModal() {
		const modal = document.getElementById('modal');
		const focusBtn = modal.querySelector('a');
		if(focusBtn) {
			focusBtn.focus();
		}

		this.imageService.modalActive = true;
	}

	getTabIndex() {
		return this.imageService.actionBarVisible === true ? '1' : '-1';
	}

	isActiveTab(x) {
		return this.imageService.activeTab == x;
	}

	getActionBarState() {
		return this.imageService.actionBarVisible;
	}

	getImageUrl() {
		return this.imageService.imageUrl;
	}
}
