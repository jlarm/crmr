import { Company, getCompanyColumns } from '@/components/companies/columns';
import {
    DataTable,
    DataTableFilters,
    PaginatedData,
} from '@/components/ui/data-table';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { useMemo } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

interface DashboardProps {
    companies: PaginatedData<Company>;
    filters: DataTableFilters;
}

export default function Dashboard({ companies, filters }: DashboardProps) {
    const dashboardUrl = dashboard().url;

    const columns = useMemo(
        () => getCompanyColumns(filters, dashboardUrl),
        [filters, dashboardUrl],
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 p-4">
                <div className="rounded-xl border bg-card p-6">
                    <div className="mb-6">
                        <h2 className="text-2xl font-semibold tracking-tight">
                            Companies
                        </h2>
                        <p className="text-sm text-muted-foreground">
                            Manage your company database
                        </p>
                    </div>
                    <DataTable
                        columns={columns}
                        data={companies}
                        filters={filters}
                        routeUrl={dashboardUrl}
                        searchPlaceholder="Search companies by name..."
                    />
                </div>
            </div>
        </AppLayout>
    );
}
