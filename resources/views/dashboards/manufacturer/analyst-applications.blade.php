@extends('layouts.dashboard')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/manufacturer.css') }}">
@endpush

@section('content')
<div class="content-card">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;">
        <i class="fas fa-industry"></i> Analyst Applications
    </h2>
    @if($applications->isEmpty())
        <table class="table">
            <thead>
                <tr>
                    <th>Analyst</th>
                    <th>Company</th>
                    <th>Status</th>
                    <th>Applied At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><img src="{{ asset('images/profile/analyst.jpeg') }}" alt="photo" width="32" class="rounded-circle me-2"> Alex Johnson</td>
                    <td>Insight Analytics</td>
                    <td><span class="badge bg-warning">Pending</span></td>
                    <td>{{ now()->format('Y-m-d') }}</td>
                    <td>
                        <a href="#" onclick="showApproveRejectInline('approve', 'Alex Johnson'); return false;" style="background: var(--success); color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none; margin-right: 4px;">Approve</a>
                        <a href="#" onclick="showApproveRejectInline('reject', 'Alex Johnson'); return false;" style="background: var(--danger); color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none; margin-right: 4px;">Reject</a>
                        <a href="#" onclick="showPortfolioInline('alex'); return false;" style="background: #0dcaf0; color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none;">View Portfolio</a>
                    </td>
                </tr>
                <tr>
                    <td><img src="{{ asset('images/profile/analyst.jpeg') }}" alt="photo" width="32" class="rounded-circle me-2"> Priya Patel</td>
                    <td>DataVision</td>
                    <td><span class="badge bg-warning">Pending</span></td>
                    <td>{{ now()->format('Y-m-d') }}</td>
                    <td>
                        <a href="#" onclick="showApproveRejectInline('approve', 'Priya Patel'); return false;" style="background: var(--success); color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none; margin-right: 4px;">Approve</a>
                        <a href="#" onclick="showApproveRejectInline('reject', 'Priya Patel'); return false;" style="background: var(--danger); color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none; margin-right: 4px;">Reject</a>
                        <a href="#" onclick="showPortfolioInline('priya'); return false;" style="background: #0dcaf0; color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none;">View Portfolio</a>
                    </td>
                </tr>
                <tr>
                    <td><img src="{{ asset('images/profile/analyst.jpeg') }}" alt="photo" width="32" class="rounded-circle me-2"> Liam Smith</td>
                    <td>MarketMinds</td>
                    <td><span class="badge bg-warning">Pending</span></td>
                    <td>{{ now()->format('Y-m-d') }}</td>
                    <td>
                        <a href="#" onclick="showApproveRejectInline('approve', 'Liam Smith'); return false;" style="background: var(--success); color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none; margin-right: 4px;">Approve</a>
                        <a href="#" onclick="showApproveRejectInline('reject', 'Liam Smith'); return false;" style="background: var(--danger); color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none; margin-right: 4px;">Reject</a>
                        <a href="#" onclick="showPortfolioInline('liam'); return false;" style="background: #0dcaf0; color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none;">View Portfolio</a>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Inline Portfolio View -->
        <div id="portfolioInlineView" style="display:none; margin-top: 2rem;"></div>

        <!-- Modal HTML -->
        <div id="portfolioModal" class="modal-overlay">
            <div class="modal-content">
                <span class="modal-close" onclick="closePortfolioModal()">&times;</span>
                <div id="portfolioModalBody">
                    <!-- Portfolio content will be injected here -->
                </div>
            </div>
        </div>

        <!-- Inline Approve/Reject Form -->
        <div id="approveRejectInlineForm" style="display:none; margin-top: 2rem; text-align:center; background: var(--background); border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.10); padding: 2.5rem 2rem; max-width: 420px; margin-left:auto; margin-right:auto;">
            <div id="approveRejectInlineIcon" style="font-size:2.5rem;margin-bottom:0.7rem;"></div>
            <div id="approveRejectInlineBody" style="font-size:1.25rem;margin-bottom:1.5rem;font-weight:500;"></div>
            <div id="approveRejectInlineActions"></div>
        </div>

        <script>
            const demoPortfolios = {
                alex: {
                    name: 'Alex Johnson',
                    title: 'PORTFOLIO ANALYST',
                    email: 'alex.johnson@email.com',
                    phone: '+1-202-555-0122',
                    location: 'Manhattan, New York',
                    linkedin: 'linkedin.com/in/alexjohnson',
                    summary: 'Motivated individual with 8 years of global-driven Financial Analyst skilled in collecting, monitoring and studying data to analyze financial status and recommend the business strategy. Experienced in qualitative and quantitative analysis, contract management and forecasting. Good presentation, relationship-building, and organizational skills.',
                    skills: [
                        'Financial Statements Analysis',
                        'Monitor Portfolio Performance',
                        'Portfolio Management Strategies',
                        'Advanced Excel',
                        'Strong Interpersonal Skills',
                        'Data Analysts',
                        'Powerpoint',
                        'Provided Reports to Stakeholders'
                    ],
                    experience: [
                        {
                            title: 'Sr. Portfolio Analyst',
                            company: 'JP Morgan Investments, NY',
                            period: 'May 2019 - Present',
                            details: [
                                'Research Global and Domestic Capital Market Indices and Updates;',
                                'Research Asset Classes and Identify Emerging Trends of Investing for the Ultra High Net Worth Individuals;',
                                'Research and Understand Ultra High Net worth Decision Process of Investing;',
                                'Use research tools for adequate output of Key Ratio’s of Portfolio to evaluate performances;',
                                'Make Pitch Books (Power Point Presentation) for Client Acquisitions;',
                                'Track Portfolio Reports regularly'
                            ]
                        },
                        {
                            title: 'Portfolio Analyst',
                            company: 'Bank of America, NY',
                            period: 'Apr 2018 - Jun 2018',
                            details: [
                                'Understanding the products and services offered under transaction banking;',
                                'Worked with the Forex department to learn about Forex products and their processing;',
                                'Conducted Client Visits to ensure if, satisfied with the products and services'
                            ]
                        }
                    ],
                    education: [
                        {
                            degree: "Master's Degree in Business Administration",
                            school: 'Columbia University, New York',
                            period: 'Jul 2017 - Apr 2019'
                        },
                        {
                            degree: 'Bachelor of Business Administration',
                            school: 'Arizona State University, Arizona',
                            period: 'Jul 2013 - May 2016'
                        }
                    ]
                },
                priya: {
                    name: 'Priya Patel',
                    title: 'DATA ANALYST',
                    email: 'priya.patel@email.com',
                    phone: '+1-202-555-0133',
                    location: 'San Francisco, CA',
                    linkedin: 'linkedin.com/in/priyapatel',
                    summary: 'Data-driven analyst with 6 years of experience in business intelligence and predictive analytics. Expert in SQL, Python, and dashboarding. Strong communicator and team player.',
                    skills: [
                        'Business Intelligence',
                        'Predictive Analytics',
                        'SQL & Python',
                        'Dashboarding',
                        'Data Visualization',
                        'Stakeholder Communication'
                    ],
                    experience: [
                        {
                            title: 'Lead Data Analyst',
                            company: 'Tech Insights, CA',
                            period: 'Jan 2021 - Present',
                            details: [
                                'Developed predictive models for customer churn;',
                                'Built interactive dashboards for executive reporting;',
                                'Led a team of 4 analysts on cross-functional projects;'
                            ]
                        },
                        {
                            title: 'Data Analyst',
                            company: 'MarketPulse, CA',
                            period: 'Aug 2017 - Dec 2020',
                            details: [
                                'Automated ETL pipelines;',
                                'Created ad-hoc reports for marketing and sales;',
                                'Presented findings to senior management;'
                            ]
                        }
                    ],
                    education: [
                        {
                            degree: 'MSc in Data Science',
                            school: 'Stanford University, CA',
                            period: '2015 - 2017'
                        },
                        {
                            degree: 'BSc in Statistics',
                            school: 'UCLA, CA',
                            period: '2011 - 2015'
                        }
                    ]
                },
                liam: {
                    name: 'Liam Smith',
                    title: 'MARKET RESEARCH ANALYST',
                    email: 'liam.smith@email.com',
                    phone: '+1-202-555-0144',
                    location: 'Chicago, IL',
                    linkedin: 'linkedin.com/in/liamsmith',
                    summary: 'Market research analyst with 5+ years of experience in consumer insights and competitive analysis. Skilled in survey design, data mining, and report writing.',
                    skills: [
                        'Consumer Insights',
                        'Competitive Analysis',
                        'Survey Design',
                        'Data Mining',
                        'Report Writing',
                        'Presentation Skills'
                    ],
                    experience: [
                        {
                            title: 'Senior Market Analyst',
                            company: 'Insight Group, IL',
                            period: 'Feb 2020 - Present',
                            details: [
                                'Designed and analyzed national consumer surveys;',
                                'Produced quarterly market trend reports;',
                                'Presented findings to C-level executives;'
                            ]
                        },
                        {
                            title: 'Market Research Associate',
                            company: 'BrandScope, IL',
                            period: 'Jun 2017 - Jan 2020',
                            details: [
                                'Collected and analyzed competitor data;',
                                'Supported new product launch research;',
                                'Maintained research databases;'
                            ]
                        }
                    ],
                    education: [
                        {
                            degree: 'MA in Marketing Research',
                            school: 'Northwestern University, IL',
                            period: '2015 - 2017'
                        },
                        {
                            degree: 'BA in Economics',
                            school: 'University of Illinois, IL',
                            period: '2011 - 2015'
                        }
                    ]
                }
            };

            function showPortfolioModal(key) {
                const data = demoPortfolios[key];
                if (!data) return;
                let html = `
                    <div class="portfolio-left">
                        <img src="${window.location.origin}/images/profile/analyst.jpeg" alt="Profile Photo" style="width:80px;height:80px;border-radius:50%;margin-bottom:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.08);object-fit:cover;">
                        <h2><i class='fas fa-user-tie'></i> ${data.name}</h2>
                        <div class="subtitle">${data.title}</div>
                        <div class="portfolio-contact">
                            <div><i class='fas fa-envelope'></i> ${data.email}</div>
                            <div><i class='fas fa-phone'></i> ${data.phone}</div>
                            <div><i class='fas fa-map-marker-alt'></i> ${data.location}</div>
                            <div><i class='fab fa-linkedin'></i> ${data.linkedin}</div>
                        </div>
                    </div>
                    <div class="portfolio-right">
                        <div class="portfolio-summary">${data.summary}</div>
                        <div class="portfolio-section">
                            <div class="portfolio-section-title">Skills</div>
                            <ul class="portfolio-list">
                                ${data.skills.map(skill => `<li>${skill}</li>`).join('')}
                            </ul>
                        </div>
                        <div class="portfolio-section">
                            <div class="portfolio-section-title">Experience</div>
                            ${data.experience.map(exp => `
                                <div style='margin-bottom:0.5rem;'><b>${exp.title}</b><br>${exp.company}, <span style='font-style:italic;'>${exp.period}</span>
                                    <ul class='portfolio-list'>${exp.details.map(d => `<li>${d}</li>`).join('')}</ul>
                                </div>
                            `).join('')}
                        </div>
                        <div class="portfolio-section">
                            <div class="portfolio-section-title">Education</div>
                            <ul class="portfolio-list">
                                ${data.education.map(edu => `<li><b>${edu.degree}</b><br>${edu.school}, <span style='font-style:italic;'>${edu.period}</span></li>`).join('')}
                            </ul>
                        </div>
                    </div>
                `;
                document.getElementById('portfolioModalBody').innerHTML = html;
                document.getElementById('portfolioModal').classList.add('active');
            }
            function closePortfolioModal() {
                document.getElementById('portfolioModal').classList.remove('active');
            }

            function showApproveRejectInline(action, name) {
                document.getElementById('approveRejectInlineIcon').innerHTML =
                    action === 'approve'
                        ? '<i class="fas fa-check-circle" style="color:var(--success);"></i>'
                        : '<i class="fas fa-times-circle" style="color:var(--danger);"></i>';
                document.getElementById('approveRejectInlineBody').innerHTML =
                    `Are you sure you want to <b style='color:var(--success);text-transform:uppercase;'>${action}</b> <span style='color:var(--primary);font-weight:600;'>${name}</span>?`;
                document.getElementById('approveRejectInlineActions').innerHTML =
                    `<button onclick=\"confirmApproveRejectInline('${action}', '${name}')\" style=\"background: var(--success); color: #fff; padding: 10px 32px; border-radius: 6px; border:none; margin-right: 18px; font-size:1.1rem; font-weight:600; box-shadow:0 2px 8px rgba(0,0,0,0.08); transition:background 0.2s;\">Yes</button>`+
                    `<button onclick=\"closeApproveRejectInline()\" style=\"background: var(--danger); color: #fff; padding: 10px 32px; border-radius: 6px; border:none; font-size:1.1rem; font-weight:600; box-shadow:0 2px 8px rgba(0,0,0,0.08); transition:background 0.2s;\">No</button>`;
                document.getElementById('approveRejectInlineForm').style.display = 'block';
                window.scrollTo({ top: document.getElementById('approveRejectInlineForm').offsetTop - 40, behavior: 'smooth' });
            }
            function closeApproveRejectInline() {
                document.getElementById('approveRejectInlineForm').style.display = 'none';
            }
            function confirmApproveRejectInline(action, name) {
                document.getElementById('approveRejectInlineIcon').innerHTML =
                    action === 'approve'
                        ? '<i class="fas fa-check-circle" style="color:var(--success);"></i>'
                        : '<i class="fas fa-times-circle" style="color:var(--danger);"></i>';
                document.getElementById('approveRejectInlineBody').innerHTML = `<b style='color:var(--primary);'>${name}</b> has been <span style='color:var(--success);text-transform:uppercase;'>${action === 'approve' ? 'approved' : 'rejected'}</span> successfully!`;
                document.getElementById('approveRejectInlineActions').innerHTML = `<button onclick=\"closeApproveRejectInline()\" style=\"background: var(--primary); color: #fff; padding: 10px 32px; border-radius: 6px; border:none; font-size:1.1rem; font-weight:600; box-shadow:0 2px 8px rgba(0,0,0,0.08); transition:background 0.2s;\">Close</button>`;
            }

            function showPortfolioInline(key) {
                const data = demoPortfolios[key];
                if (!data) return;
                let html = `
                    <div class=\"modal-content\" style=\"display:flex;flex-direction:row;max-width:900px;margin:0 auto;box-shadow:0 4px 16px rgba(0,0,0,0.10);border-radius:12px;overflow:hidden;\">
                        <div class=\"portfolio-left\">
                            <img src=\"${window.location.origin}/images/profile/analyst.jpeg\" alt=\"Profile Photo\" style=\"width:80px;height:80px;border-radius:50%;margin-bottom:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.08);object-fit:cover;\">
                            <h2><i class='fas fa-user-tie'></i> ${data.name}</h2>
                            <div class=\"subtitle\">${data.title}</div>
                            <div class=\"portfolio-contact\">
                                <div><i class='fas fa-envelope'></i> ${data.email}</div>
                                <div><i class='fas fa-phone'></i> ${data.phone}</div>
                                <div><i class='fas fa-map-marker-alt'></i> ${data.location}</div>
                                <div><i class='fab fa-linkedin'></i> ${data.linkedin}</div>
                            </div>
                        </div>
                        <div class=\"portfolio-right\">
                            <div class=\"portfolio-summary\">${data.summary}</div>
                            <div class=\"portfolio-section\">
                                <div class=\"portfolio-section-title\">Skills</div>
                                <ul class=\"portfolio-list\">${data.skills.map(skill => `<li>${skill}</li>`).join('')}</ul>
                            </div>
                            <div class=\"portfolio-section\">
                                <div class=\"portfolio-section-title\">Experience</div>
                                ${data.experience.map(exp => `
                                    <div style='margin-bottom:0.5rem;'><b>${exp.title}</b><br>${exp.company}, <span style='font-style:italic;'>${exp.period}</span>
                                        <ul class='portfolio-list'>${exp.details.map(d => `<li>${d}</li>`).join('')}</ul>
                                    </div>
                                `).join('')}
                            </div>
                            <div class=\"portfolio-section\">
                                <div class=\"portfolio-section-title\">Education</div>
                                <ul class=\"portfolio-list\">${data.education.map(edu => `<li><b>${edu.degree}</b><br>${edu.school}, <span style='font-style:italic;'>${edu.period}</span></li>`).join('')}</ul>
                            </div>
                        </div>
                    </div>
                    <div style=\"text-align:right;max-width:900px;margin:0 auto;\"><button onclick=\"closePortfolioInline()\" style=\"margin-top:1.2rem;background:var(--primary);color:#fff;padding:8px 24px;border-radius:6px;border:none;font-size:1rem;font-weight:600;box-shadow:0 2px 8px rgba(0,0,0,0.08);\">Close</button></div>
                `;
                document.getElementById('portfolioInlineView').innerHTML = html;
                document.getElementById('portfolioInlineView').style.display = 'block';
                window.scrollTo({ top: document.getElementById('portfolioInlineView').offsetTop - 40, behavior: 'smooth' });
            }
            function closePortfolioInline() {
                document.getElementById('portfolioInlineView').style.display = 'none';
                document.getElementById('portfolioInlineView').innerHTML = '';
            }
        </script>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>Analyst</th>
                <th>Company</th>
                <th>Status</th>
                <th>Applied At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($applications as $app)
            <tr>
                <td>
                    <img src="{{ asset($app->analyst_photo ?? 'images/profile/analyst.jpeg') }}" alt="photo" width="32" class="rounded-circle me-2">
                    {{ $app->analyst_name }}
                </td>
                <td>{{ $app->analyst_company }}</td>
                <td>
                    @if($app->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($app->status == 'approved')
                        <span class="badge bg-success">Approved</span>
                    @else
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($app->created_at)->format('Y-m-d') }}</td>
                <td>
                    @if($app->status == 'pending')
                        <a href="{{ route('manufacturer.approveAnalyst', $app->id) }}" class="btn btn-success btn-sm">Approve</a>
                        <a href="{{ route('manufacturer.rejectAnalyst', $app->id) }}" class="btn btn-danger btn-sm">Reject</a>
                    @endif
                    <a href="{{ route('manufacturer.viewAnalystPortfolio', $app->analyst_id) }}" class="btn btn-info btn-sm">View Portfolio</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection 