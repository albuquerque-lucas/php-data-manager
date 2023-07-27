import CheckboxFormController from "./CheckboxFormController.js";
import CellEditController from "./CellEditController.js";

export default class AppController {
  constructor() {
    console.log('Inicia controlador App');
    this.checkboxFormController = new CheckboxFormController();
    this.cellEditController = new CellEditController();
  }
}