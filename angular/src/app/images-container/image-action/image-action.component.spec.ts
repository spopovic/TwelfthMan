import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ImageActionComponent } from './image-action.component';

describe('ImageActionComponent', () => {
	let component: ImageActionComponent;
	let fixture: ComponentFixture<ImageActionComponent>;

	beforeEach(async(() => {
		TestBed.configureTestingModule({
			declarations: [ImageActionComponent]
		})
			.compileComponents();
	}));

	beforeEach(() => {
		fixture = TestBed.createComponent(ImageActionComponent);
		component = fixture.componentInstance;
		fixture.detectChanges();
	});

	it('should create', () => {
		expect(component).toBeTruthy();
	});
});
