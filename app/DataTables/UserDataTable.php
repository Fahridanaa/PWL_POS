<?php

namespace App\DataTables;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
	/**
	 * Build the DataTable class.
	 *
	 * @param QueryBuilder $query Results from query() method.
	 */
	public function dataTable(QueryBuilder $query): EloquentDataTable
	{
		return (new EloquentDataTable($query))
			->addColumn('level_kode', function ($row) {
				return $row->level->level_kode;
			})
			->addColumn('level_name', function ($row) {
				return $row->level->level_nama;
			})
			->addColumn('Show', function ($row) {
				return '<a class="edit btn btn-primary btn-sm" href="' . route('m_user.show', $row->user_id) . '">show</a>';
			})
			->addColumn('Edit', function ($row) {
				return '<a class="edit btn btn-primary btn-sm" href="' . route('m_user.edit', $row->user_id) . '">edit</a>';
			})
			->addColumn('Delete', function ($row) {
				$token = csrf_token();
				$url = route('m_user.destroy', $row->user_id);
				return <<<HTML
            <form action="$url" method="POST">
                <input type="hidden" name="_token" value="$token">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">delete</button>
            </form>
            HTML;
			})
			->rawColumns(['level_kode', 'level_nama', 'Show', 'Edit', 'Delete'])
			->setRowId('id');
	}

	/**
	 * Get the query source of dataTable.
	 */
	public function query(UserModel $model): QueryBuilder
	{
		return $model->newQuery();
	}

	/**
	 * Optional method if you want to use the html builder.
	 */
	public function html(): HtmlBuilder
	{
		return $this->builder()
			->setTableId('user-table')
			->columns($this->getColumns())
			->minifiedAjax()
			//->dom('Bfrtip')
			->orderBy(2)
			->selectStyleSingle()
			->buttons([
				Button::make('excel'),
				Button::make('csv'),
				Button::make('pdf'),
				Button::make('print'),
				Button::make('reset'),
				Button::make('reload')
			]);
	}

	/**
	 * Get the dataTable columns definition.
	 */
	public function getColumns(): array
	{
		return [
			Column::make('user_id'),
			Column::make('level_id'),
			Column::computed('level_kode'),
			Column::computed('level_name'),
			Column::make('username'),
			Column::make('name'),
			Column::computed('Show')
				->exportable(false)
				->printable(false)
				->width(60)
				->addClass(
					'text-center'
				),
			Column::computed('Edit')
				->exportable(false)
				->printable(false)
				->width(60)
				->addClass(
					'text-center'
				),
			Column::computed('Delete')
				->exportable(false)
				->printable(false)
				->width(60)
				->addClass(
					'text-center'
				),
		];
	}

	/**
	 * Get the filename for export.
	 */
	protected function filename(): string
	{
		return 'User_' . date('YmdHis');
	}
}