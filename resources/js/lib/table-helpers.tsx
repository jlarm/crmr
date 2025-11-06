import { Button } from '@/components/ui/button';
import { DataTableFilters } from '@/components/ui/data-table';
import { router } from '@inertiajs/react';
import { ArrowDown, ArrowUp, ArrowUpDown } from 'lucide-react';

interface SortableHeaderProps {
    column: string;
    label: string;
    filters: DataTableFilters;
    routeUrl: string;
}

export function SortableHeader({
    column,
    label,
    filters,
    routeUrl,
}: SortableHeaderProps) {
    const handleSort = () => {
        const newDirection =
            filters.sort_by === column && filters.sort_direction === 'asc'
                ? 'desc'
                : 'asc';

        router.get(
            routeUrl,
            {
                ...filters,
                sort_by: column,
                sort_direction: newDirection,
            },
            {
                preserveState: true,
                preserveScroll: true,
            },
        );
    };

    const getSortIcon = () => {
        if (filters.sort_by !== column) {
            return <ArrowUpDown className="ml-2 size-4" />;
        }

        return filters.sort_direction === 'asc' ? (
            <ArrowUp className="ml-2 size-4" />
        ) : (
            <ArrowDown className="ml-2 size-4" />
        );
    };

    return (
        <Button variant="ghost" onClick={handleSort}>
            {label}
            {getSortIcon()}
        </Button>
    );
}
