export default class CellEditController {
  constructor() {
    this.textCells = document.querySelectorAll('.cell-value');
    this.textForms = document.querySelectorAll('.text-form');
    this.tdCell = document.querySelectorAll('.input-cell');
    this.hiddenInputs = document.querySelectorAll('.input-hidden');
    this.editMode = false;

    if (this.tdCell.length > 0) {
      this.tdCell.forEach((cell) => {
        cell.addEventListener('dblclick', () => {
          const spanTag = cell.querySelector('.cell-value');
          const cellInput = cell.querySelector('.input-hidden');
          let column;

          if (this.editMode === false) {
            this.toggleElementVisibility(cellInput, spanTag);
            this.editMode = true;
          } else {
            this.toggleElementVisibility(spanTag, cellInput);
            if (cellInput.dataset.namecell === 'task_name') {
              column = 'task_name';
            } else {
              column = 'task_description';
            }
            this.updateTextData(spanTag.dataset.id, cellInput.value, column);
            spanTag.textContent = cellInput.value;
            this.editMode = false;
          }
        });
      });
    }
  } 

  toggleElementVisibility(elementToShow, elementToHide) {
    elementToShow.classList.remove('d-none');
    elementToHide.classList.add('d-none');
  }

  async updateTextData(taskId, text, column) {
    // const url = '/update-name-description';
    const url = '/update-task';
    const data = {
      taskId,
      text,
      column,
    };
    console.log(data);
    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    };
    try {
      const response = await fetch(url, options);
      if (response.ok) {
        console.log(`UPDATE tasks SET ${data.column} = ${data.text} WHERE task_id = ${taskId}`);
        console.log('Dados enviados com sucesso!');
        console.log(options.body);
      }
    } catch (error) {
      console.log('Erro ao enviar os dados: ', error);
    }
  }
}