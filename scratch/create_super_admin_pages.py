import os
import re

public_dir = '/var/www/hospital-management/frontend/public'
dash_file = os.path.join(public_dir, 'super-admin-dashboard.html')

with open(dash_file, 'r') as f:
    content = f.read()

# Update the sidebar links in the content first
sidebar_replacements = {
    'href="#clinics"': 'href="super-admin-clinics.html"',
    'href="#plans"': 'href="super-admin-plans.html"',
    'href="#revenue"': 'href="super-admin-revenue.html"',
}

for old, new in sidebar_replacements.items():
    content = content.replace(old, new)

# Write back to dashboard
with open(dash_file, 'w') as f:
    # Need to make sure the active class is correct for dashboard
    dash_content = content
    f.write(dash_content)

# Define the new pages and their specific main content
pages = {
    'super-admin-clinics.html': {
        'title': 'Tenant Clinics Management',
        'active_link': 'href="super-admin-clinics.html"',
        'content': '''
    <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-3 rounded-3 shadow-sm border">
      <div>
        <h3 class="fw-bold mb-0">Tenant Clinics Management</h3>
        <p class="text-muted mb-0 small">Manage, suspend, or configure hospital tenants.</p>
      </div>
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-primary"><i class="ti ti-plus me-1"></i> Add New Clinic</button>
      </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">All Clinics</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Clinic Name</th>
                            <th>Domain/Slug</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="clinics-table-body">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
'''
    },
    'super-admin-plans.html': {
        'title': 'Subscription Plans',
        'active_link': 'href="super-admin-plans.html"',
        'content': '''
    <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-3 rounded-3 shadow-sm border">
      <div>
        <h3 class="fw-bold mb-0">Subscription Plans</h3>
        <p class="text-muted mb-0 small">Manage SaaS pricing tiers and features.</p>
      </div>
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-primary"><i class="ti ti-plus me-1"></i> Create Plan</button>
      </div>
    </div>
    <div class="row g-4">
        <!-- Plan Cards -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center p-4">
                    <h4 class="fw-bold">Basic</h4>
                    <h2 class="display-5 fw-bold my-3">$99<span class="fs-14 text-muted fw-normal">/mo</span></h2>
                    <ul class="list-unstyled text-start mb-4">
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Up to 5 Doctors</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i> 1000 Patients/mo</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Basic Support</li>
                    </ul>
                    <button class="btn btn-outline-primary w-100">Edit Plan</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-primary h-100 position-relative">
                <div class="position-absolute top-0 start-50 translate-middle badge bg-primary rounded-pill">Most Popular</div>
                <div class="card-body text-center p-4">
                    <h4 class="fw-bold text-primary">Pro</h4>
                    <h2 class="display-5 fw-bold my-3 text-primary">$199<span class="fs-14 text-muted fw-normal">/mo</span></h2>
                    <ul class="list-unstyled text-start mb-4">
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Up to 20 Doctors</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Unlimited Patients</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Priority Support</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Analytics Dashboard</li>
                    </ul>
                    <button class="btn btn-primary w-100">Edit Plan</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center p-4">
                    <h4 class="fw-bold">Enterprise</h4>
                    <h2 class="display-5 fw-bold my-3">$499<span class="fs-14 text-muted fw-normal">/mo</span></h2>
                    <ul class="list-unstyled text-start mb-4">
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Unlimited Doctors</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Unlimited Patients</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i> 24/7 Dedicated Support</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Custom Domain</li>
                    </ul>
                    <button class="btn btn-outline-primary w-100">Edit Plan</button>
                </div>
            </div>
        </div>
    </div>
'''
    },
    'super-admin-revenue.html': {
        'title': 'MRR Analytics',
        'active_link': 'href="super-admin-revenue.html"',
        'content': '''
    <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-3 rounded-3 shadow-sm border">
      <div>
        <h3 class="fw-bold mb-0">MRR Analytics</h3>
        <p class="text-muted mb-0 small">Monthly Recurring Revenue and platform financial health.</p>
      </div>
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-secondary"><i class="ti ti-download me-1"></i> Export Report</button>
      </div>
    </div>
    
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-4">
                <h6 class="text-muted mb-2">Total MRR</h6>
                <h2 class="fw-bold text-success mb-0">$12,450</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-4">
                <h6 class="text-muted mb-2">Annual Run Rate (ARR)</h6>
                <h2 class="fw-bold text-primary mb-0">$149,400</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-4">
                <h6 class="text-muted mb-2">Avg Revenue Per User (ARPU)</h6>
                <h2 class="fw-bold text-info mb-0">$84.12</h2>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-4">Revenue Growth Chart</h5>
            <div class="alert alert-info border-0 bg-info-subtle text-info">
                <i class="ti ti-info-circle me-1"></i> Chart integration pending - displaying placeholder data.
            </div>
            <div style="height: 300px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #adb5bd;">
                <i class="ti ti-chart-bar" style="font-size: 64px;"></i>
            </div>
        </div>
    </div>
'''
    }
}

# Regex to match the main content block
# We want to replace everything from <main class="super-content"> to </main> (or before the first script tag at the bottom)
main_pattern = re.compile(r'(<main class="super-content">)(.*?)(</main>)', re.DOTALL)

for page_name, page_data in pages.items():
    page_content = content
    
    # Change active link
    # First, remove active from dashboard
    page_content = page_content.replace('href="super-admin-dashboard.html" class="nav-link active"', 'href="super-admin-dashboard.html" class="nav-link"')
    # Add active to current link
    page_content = page_content.replace(f'{page_data["active_link"]}" class="nav-link"', f'{page_data["active_link"]}" class="nav-link active"')
    
    # Change title
    page_content = re.sub(r'<title>.*?</title>', f'<title>{page_data["title"]} - Preclinic SaaS</title>', page_content)
    
    # Replace main content
    new_main = r'\1' + '\n' + page_data["content"] + '\n' + r'\3'
    page_content = main_pattern.sub(new_main, page_content)
    
    # Write file
    with open(os.path.join(public_dir, page_name), 'w') as f:
        f.write(page_content)

print("Super admin pages created and updated successfully.")
