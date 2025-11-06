import * as React from 'react';
import {
    ColumnDef,
    flexRender,
    getCoreRowModel,
    useReactTable,
} from '@tanstack/react-table';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/react';
import { ChevronLeft, ChevronRight } from 'lucide-react';

// Generic Laravel pagination structure
export interface PaginatedData<TData> {
    data: TData[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

// Query filters
export interface DataTableFilters {
    search?: string;
    sort_by?: string;
    sort_direction?: 'asc' | 'desc';
    per_page?: number;
}

interface DataTableProps<TData> {
    columns: ColumnDef<TData>[];
    data: PaginatedData<TData>;
    filters: DataTableFilters;
    routeUrl: string; // The route URL to call
    searchPlaceholder?: string;
    searchable?: boolean;
    perPageOptions?: number[];
}

export function DataTable<TData>({
                                     columns,
                                     data,
                                     filters,
                                     routeUrl,
                                     searchPlaceholder = 'Search...',
                                     searchable = true,
                                     perPageOptions = [10, 25, 50, 100],
                                 }: DataTableProps<TData>) {
    // Local state for search input
    const [search, setSearch] = React.useState(filters.search || '');

    // Initialize TanStack Table with generic data
    const table = useReactTable({
        data: data.data,
        columns,
        getCoreRowModel: getCoreRowModel(),
        manualPagination: true,
        manualSorting: true,
        manualFiltering: true,
        pageCount: data.last_page,
    });

    // Debounced search
    React.useEffect(() => {
        if (!searchable) {
            return;
        }

        const timer = setTimeout(() => {
            if (search !== filters.search) {
                router.get(
                    routeUrl,
                    { ...filters, search, page: 1 },
                    {
                        preserveState: true,
                        preserveScroll: true,
                    }
                );
            }
        }, 300);

        return () => clearTimeout(timer);
    }, [search, searchable, routeUrl, filters]);

    // Per-page handler
    const handlePerPageChange = (value: string) => {
        router.get(
            routeUrl,
            { ...filters, per_page: parseInt(value), page: 1 },
            { preserveState: true, preserveScroll: true }
        );
    };

    // Page navigation handler
    const handlePageChange = (url: string | null) => {
        if (!url) {
            return;
        }

        router.get(url, {}, { preserveState: true, preserveScroll: true });
    };

    return (
        <div className="w-full space-y-4">
            {/* Toolbar */}
            <div className="flex items-center justify-between gap-4">
                {searchable && (
                    <Input
                        placeholder={searchPlaceholder}
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        className="max-w-sm"
                    />
                )}
                <Select
                    value={filters.per_page?.toString() || '25'}
                    onValueChange={handlePerPageChange}
                >
                    <SelectTrigger className="w-[180px]">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        {perPageOptions.map((option) => (
                            <SelectItem key={option} value={option.toString()}>
                                {option} per page
                            </SelectItem>
                        ))}
                    </SelectContent>
                </Select>
            </div>

            {/* Table */}
            <div className="rounded-md border">
                <Table>
                    <TableHeader>
                        {table.getHeaderGroups().map((headerGroup) => (
                            <TableRow key={headerGroup.id}>
                                {headerGroup.headers.map((header) => (
                                    <TableHead key={header.id}>
                                        {header.isPlaceholder
                                            ? null
                                            : flexRender(
                                                header.column.columnDef
                                                    .header,
                                                header.getContext()
                                            )}
                                    </TableHead>
                                ))}
                            </TableRow>
                        ))}
                    </TableHeader>
                    <TableBody>
                        {table.getRowModel().rows?.length ? (
                            table.getRowModel().rows.map((row) => (
                                <TableRow
                                    key={row.id}
                                    data-state={
                                        row.getIsSelected() && 'selected'
                                    }
                                >
                                    {row.getVisibleCells().map((cell) => (
                                        <TableCell key={cell.id}>
                                            {flexRender(
                                                cell.column.columnDef.cell,
                                                cell.getContext()
                                            )}
                                        </TableCell>
                                    ))}
                                </TableRow>
                            ))
                        ) : (
                            <TableRow>
                                <TableCell
                                    colSpan={columns.length}
                                    className="h-24 text-center"
                                >
                                    No results found.
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
            </div>

            {/* Pagination */}
            <div className="flex items-center justify-between">
                <div className="text-sm text-muted-foreground">
                    {data.total === 0 ? (
                        'No results'
                    ) : (
                        <>
                            Showing {data.from} to {data.to} of {data.total}{' '}
                            results
                        </>
                    )}
                </div>
                <div className="flex items-center gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        onClick={() =>
                            handlePageChange(
                                data.links.find((l) =>
                                    l.label.includes('Previous')
                                )?.url || null
                            )
                        }
                        disabled={data.current_page === 1}
                    >
                        <ChevronLeft className="size-4" />
                        Previous
                    </Button>

                    <div className="text-sm font-medium">
                        Page {data.current_page} of {data.last_page}
                    </div>

                    <Button
                        variant="outline"
                        size="sm"
                        onClick={() =>
                            handlePageChange(
                                data.links.find((l) =>
                                    l.label.includes('Next')
                                )?.url || null
                            )
                        }
                        disabled={data.current_page === data.last_page}
                    >
                        Next
                        <ChevronRight className="size-4" />
                    </Button>
                </div>
            </div>
        </div>
    );
}
