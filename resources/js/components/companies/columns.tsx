import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { DataTableFilters } from '@/components/ui/data-table';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SortableHeader } from '@/lib/table-helpers';
import { ColumnDef } from '@tanstack/react-table';
import { MoreHorizontal } from 'lucide-react';

export interface Company {
    id: number;
    name: string;
    city: string;
    state: string;
    phone: string;
    status: string;
    rating: string;
    type: string;
}

export const getCompanyColumns = (
    filters: DataTableFilters,
    routeUrl: string,
): ColumnDef<Company>[] => [
    {
        accessorKey: 'name',
        header: () => (
            <SortableHeader
                column="name"
                label="Name"
                filters={filters}
                routeUrl={routeUrl}
            />
        ),
        cell: ({ row }) => (
            <div className="font-medium">{row.getValue('name')}</div>
        ),
    },
    {
        id: 'location',
        header: 'Location',
        cell: ({ row }) => {
            const city = row.original.city;
            const state = row.original.state;
            return (
                <div className="text-sm">
                    {city}, {state}
                </div>
            );
        },
    },
    {
        accessorKey: 'phone',
        header: 'Phone',
        cell: ({ row }) => (
            <div className="font-mono text-sm">{row.getValue('phone')}</div>
        ),
    },
    {
        accessorKey: 'status',
        header: () => (
            <SortableHeader
                column="status"
                label="Status"
                filters={filters}
                routeUrl={routeUrl}
            />
        ),
        cell: ({ row }) => {
            const status = row.getValue('status') as string;
            return (
                <Badge
                    variant={status === 'active' ? 'default' : 'secondary'}
                    className="capitalize"
                >
                    {status}
                </Badge>
            );
        },
    },
    {
        accessorKey: 'rating',
        header: () => (
            <SortableHeader
                column="rating"
                label="Rating"
                filters={filters}
                routeUrl={routeUrl}
            />
        ),
        cell: ({ row }) => (
            <div className="capitalize">{row.getValue('rating')}</div>
        ),
    },
    {
        accessorKey: 'type',
        header: () => (
            <SortableHeader
                column="type"
                label="Type"
                filters={filters}
                routeUrl={routeUrl}
            />
        ),
        cell: ({ row }) => (
            <div className="capitalize">{row.getValue('type')}</div>
        ),
    },
    {
        id: 'actions',
        cell: ({ row }) => {
            const company = row.original;

            return (
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="ghost" className="size-8 p-0">
                            <span className="sr-only">Open Menu</span>
                            <MoreHorizontal className="size-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>Actions</DropdownMenuLabel>
                        <DropdownMenuItem
                            onClick={() =>
                                navigator.clipboard.writeText(
                                    company.id.toString(),
                                )
                            }
                        >
                            Copy company ID
                        </DropdownMenuItem>
                        <DropdownMenuItem>View details</DropdownMenuItem>
                        <DropdownMenuItem>Edit company</DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            );
        },
    },
];
