export default class CellEditController {
  constructor() {
    this.textDataCells = document.querySelectorAll('.task-text-data');
    this.textForms = document.querySelectorAll('.text-form');

    this.textDataCells.forEach((textData) => {
      textData.addEventListener('dblclick', () => {
        this.toggleEditMode(textData);
      });
    });

    this.textForms.forEach((form) => {
      form.addEventListener('dblclick', () => {
        this.toggleEditMode(form.previousElementSibling);
      });
    });
  }

  toggleElementVisibility(element) {
    element.classList.toggle('d-none');
  }

  toggleEditMode(element) {
    this.toggleElementVisibility(element);
    this.toggleElementVisibility(element.nextElementSibling);
  }
}