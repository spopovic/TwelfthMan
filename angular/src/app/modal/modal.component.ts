import { Component, OnInit } from '@angular/core';
import { ImagesService } from '../services/images.service';

@Component({
	selector: 'app-modal',
	templateUrl: './modal.component.html',
	styleUrls: ['./modal.component.scss']
})
export class ModalComponent implements OnInit {

	constructor(
		private imageService: ImagesService
	) { }

	ngOnInit() {
	}

	deleteCard() {
		this.imageService.deleteImage();
	}

	getModalState() {
		return this.imageService.modalActive;
	}

	setModalState(x) {
		this.imageService.modalActive = x;
	}
}
