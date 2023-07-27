export default class CheckboxFormController {
  constructor() {
    this.checkboxForms = document.querySelectorAll('.checkbox-form');
    this.statusCheckboxes = document.querySelectorAll('.status-checkbox');
    
    this.statusCheckboxes.forEach((checkbox) => {
      checkbox.addEventListener('change', this.handleSubmit.bind(this, checkbox));
    });
  }
  
  handleSubmit(checkbox) {
    const form = checkbox.closest('.checkbox-form');
    form.submit();
  }
}