import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { Ng4FilesModule } from 'angular4-files-upload';

import { AppComponent } from './app.component';
import { AppRoutingModule } from './app.routing.service';
import { NavigationComponent } from './navigation/navigation.component';
import { ImagesContainerComponent } from './images-container/images-container.component';
import { HeaderComponent } from './header/header.component';
import { ImageToggleComponent } from './images-container/image-toggle/image-toggle.component';
import { ImageActionComponent } from './images-container/image-action/image-action.component';
import { ModalComponent } from './modal/modal.component';
import { FormComponent } from './form/form.component';

import { DownloadService } from './services/download.service';
import { ImagesService } from './services/images.service';
import { UploadService } from './services/upload.service';

@NgModule({
	declarations: [
		AppComponent,
		NavigationComponent,
		ImagesContainerComponent,
		HeaderComponent,
		ImageToggleComponent,
		ImageActionComponent,
		ModalComponent,
		FormComponent
	],
	imports: [
		BrowserModule,
		AppRoutingModule,
		FormsModule,
		HttpModule,
		Ng4FilesModule
	],
	providers: [
		DownloadService,
		ImagesService,
		UploadService
	],
	bootstrap: [AppComponent]
})
export class AppModule { }
