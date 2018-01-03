import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ImagesContainerComponent } from './images-container/images-container.component';
import { FormComponent } from './form/form.component';

const appRoutes: Routes = [
	{ path: '', component: ImagesContainerComponent, pathMatch: 'full' },
	{ path: 'form', component: FormComponent  },
];


@NgModule({
	imports: [RouterModule.forRoot(appRoutes)],
	exports: [RouterModule]
})

export class AppRoutingModule {

}
