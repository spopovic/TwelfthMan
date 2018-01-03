import { Component, OnInit } from '@angular/core';
import { Ng4FilesStatus, Ng4FilesSelected, Ng4FilesService, Ng4FilesConfig } from 'angular4-files-upload';
import { UploadService } from '../services/upload.service';
import { Response } from '@angular/http';
import { Router } from '@angular/router';

const FILENAME_MISSING = 'Please type a filename.';
const FILE_MISSING = 'Please select a file.';
const UPLOAD_ERROR = 'Error during upload';
const UPLOAD_SUCCESSFULL = 'Upload successfull!';

@Component({
	selector: 'app-form',
	templateUrl: './form.component.html',
	styleUrls: ['./form.component.scss']
})
export class FormComponent implements OnInit {

	public selectedFiles: File[];
	fileName: string;
	status: string;

	private config: Ng4FilesConfig = {
		acceptExtensions: ['jpg', 'jpeg'],
		maxFilesCount: 5,
		maxFileSize: 1000,
		totalFilesSize: 1000
	};

	constructor(private ng4FilesService: Ng4FilesService, private uploadService: UploadService, private router: Router) {
		this.fileName = '';
		this.status = '';
	}

	public filesSelect(selectedFiles: Ng4FilesSelected): void {
		if (selectedFiles.status !== Ng4FilesStatus.STATUS_SUCCESS) {
			this.selectedFiles = selectedFiles.files;
			this.status = this.fileName ? '' : FILENAME_MISSING;
			return;
		}
	}

	upload() {
		if (this.fileName && this.selectedFiles) {
			this.uploadService
			.uploadImage(this.fileName, this.selectedFiles)
			.subscribe((response: Response) => {
				if (response.ok) {
					this.status = '';
					this.fileName = '';
					this.selectedFiles = undefined;
					this.router.navigate(['/']);
				}
			},
			(error) => {
				// Error happened during the upload
				this.status = UPLOAD_ERROR;
			});
		} else {
			// This could be done using Angular Forms,
			// FormGroup and FormValidaition - but as situation is not too complex - we can do it on this way too
			this.handleValidation();
		}
	}

	handleValidation() {
		if (!this.fileName) {
			this.status = FILENAME_MISSING;
		}
		if (!this.selectedFiles) {
			this.status = FILE_MISSING;
		}
	}

	ngOnInit() {
		this.ng4FilesService.addConfig(this.config);
	}

}
