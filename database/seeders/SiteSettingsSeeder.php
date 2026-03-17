<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'hero' => [
                'title' => 'Arquitetura como narrativa espacial',
                'subtitle' => 'Projetos residenciais e comerciais guiados por luz, contexto e identidade.',
            ],
            'about' => [
                'text' => 'Arquitetura como experiencia. Cada projeto nasce do dialogo entre espaco, contexto e quem o habita.',
                'image_path' => null,
            ],
            'process' => [
                'title' => 'Processo',
                'steps' => [
                    ['title' => 'Contexto', 'description' => 'Leitura do lugar, da cidade e das relacoes que o espaco propoe.'],
                    ['title' => 'Conceito', 'description' => 'Traducao das necessidades em uma ideia clara e estruturadora.'],
                    ['title' => 'Forma', 'description' => 'Materializacao do conceito em arquitetura precisa e atemporal.'],
                ],
            ],
            'experience' => [
                'title' => 'Atuação',
                'subtitle' => 'Arquitetura aplicada a diferentes escalas e contextos, sempre com atencao ao lugar e as pessoas.',
                'blocks' => [
                    ['title' => 'Tipologias', 'items' => ['Residencial', 'Comercial', 'Institucional', 'Interiores']],
                    ['title' => 'Experiencia', 'items' => ['+6 anos de pratica profissional', 'Projetos executivos e acompanhamento de obra', 'Atuação do conceito a execucao']],
                    ['title' => 'Atuação', 'items' => ['Brasil', 'Projetos remotos', 'Contextos urbanos e naturais']],
                ],
            ],
            'contact' => [
                'title' => 'Contato',
                'description' => 'Vamos conversar sobre o seu projeto ou tirar duvidas.',
                'instagram_url' => 'https://www.instagram.com/',
                'linkedin_url' => 'https://www.linkedin.com/',
                'email' => 'contato@exemplo.com',
                'whatsapp_number' => '5551999999999',
                'whatsapp_message' => 'Ola! Gostaria de falar sobre um projeto.',
            ],
            'footer' => [
                'brand_name' => 'Iara Tedesco',
                'brand_subtitle' => 'Arquitetura e Urbanismo',
                'email' => 'contato@exemplo.com',
                'phone' => '+55 (51) 9999-9999',
                'city' => 'Passo Fundo, RS',
                'instagram_url' => '#',
                'linkedin_url' => '#',
                'copyright' => '© 2026 Todos os direitos reservados',
                'cau' => 'CAU/BR A304967-1',
            ],
            'featured_projects' => [
                'title' => 'Projetos Selecionados',
                'description' => 'Uma curadoria dos trabalhos mais representativos.',
            ],
            'footer_services' => [
                'title' => 'Serviços',
                'items' => [
                    'Projetos Arquitetônicos',
                    'Interiores',
                    'Consultoria',
                ],
            ],
            'seo' => [
                'title' => 'Iara Tedesco | Arquitetura & Urbanismo',
                'description' => 'Iara Tedesco — Arquitetura & Urbanismo. Projetos residenciais e comerciais guiados por luz, contexto e identidade.',
            ],
            'navbar' => [
                'brand_name' => 'Iara Tedesco',
                'brand_role' => 'Arquitetura & Urbanismo',
                'home_label' => 'Home',
                'projects_label' => 'Projetos',
                'about_label' => 'Sobre',
                'contact_label' => 'Contato',
            ],
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }
    }
}
