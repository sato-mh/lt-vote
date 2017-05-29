import argparse
import yaml
from ltvote import api, orm, repository


def run_ltvote(args):
    with open(args.config, 'r') as f:
        config = yaml.load(f)
    ormapper = orm.ORM(config['ormapper']['url'])
    vote_repository_factory = repository.VoteRepositoryFactory
    app = api.VoteApp(ormapper, vote_repository_factory)
    print('ltvote start')
    app.run(host=config['app']['host'],
            port=config['app']['port'],
            debug=config['app']['debug'])
    print('ltvote shutdown')


def parse_args():
    parser = argparse.ArgumentParser()
    parser.add_argument('-c', '--config', default='config/default.yml')
    return parser.parse_args()


if __name__ == '__main__':
    args = parse_args()
    run_ltvote(args)
