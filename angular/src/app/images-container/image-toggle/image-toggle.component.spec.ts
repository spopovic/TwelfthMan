import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ImageToggleComponent } from './image-toggle.component';

describe('ImageToggleComponent', () => {
	let component: ImageToggleComponent;
	let fixture: ComponentFixture<ImageToggleComponent>;

	beforeEach(async(() => {
		TestBed.configureTestingModule({
			declarations: [ImageToggleComponent]
		})
			.compileComponents();
	}));

	beforeEach(() => {
		fixture = TestBed.createComponent(ImageToggleComponent);
		component = fixture.componentInstance;
		fixture.detectChanges();
	});

	it('should create', () => {
		expect(component).toBeTruthy();
	});
});
